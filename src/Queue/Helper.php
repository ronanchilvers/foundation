<?php

namespace Ronanchilvers\Foundation\Queue;

use Carbon\Carbon;
use Exception;
use Pheanstalk\Contract\PheanstalkInterface;
use Pheanstalk\Pheanstalk;
use Psr\Log\LoggerInterface;
use Ronanchilvers\Foundation\Psr\Traits\LoggerAwareTrait;
use Ronanchilvers\Foundation\Queue\Exception\FailedDispatchException;
use Ronanchilvers\Foundation\Queue\Exception\FailedJobException;
use Ronanchilvers\Foundation\Queue\Exception\FatalException;
use Ronanchilvers\Foundation\Queue\Exception\InvalidPayloadException;
use Ronanchilvers\Foundation\Queue\Handler\ClassJobHandler;
use Ronanchilvers\Foundation\Queue\Job\DispatchableInterface;
use Ronanchilvers\Foundation\Queue\Job\JobInterface;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Helper for queue operations
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
class Helper
{
    use LoggerAwareTrait;

    /**
     * @var Pheanstalk\Connection
     */
    protected $connection;

    /**
     * @var string
     */
    protected $breakFile;

    /**
     * Class constructor
     *
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function __construct(
        LoggerInterface $logger,
        Pheanstalk $connection,
        string $breakfile
    ) {
        $this->setLogger($logger);
        $this->connection = $connection;
        $this->breakFile = $breakfile;
    }

    /**
     * Dispatch a job to the queue
     *
     * @param Ronanchilvers\Foundation\Queue\Job\JobInterface $job
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function dispatch(DispatchableInterface $dispatchable)
    {
        $data = [
            'class' => $dispatchable->getClass(),
            'args'  => $dispatchable->getArgs()
        ];

        $queue = $dispatchable->getQueue();
        $delay = $dispatchable->getDelay();
        if ($delay instanceof Carbon) {
            $now = Carbon::now();
            if ($delay > $now) {
                $delay = $delay->getTimestamp() - $now->getTimestamp();
            }
        }
        if (!is_int($delay)) {
            $delay = 0;
        }

        try {
            return $this->connection
                ->useTube($queue)
                ->put(
                    serialize($data),
                    PheanstalkInterface::DEFAULT_PRIORITY,
                    $delay
                );
        } catch (Exception $ex) {
            throw new FailedDispatchException(
                'Unable to queue job',
                0,
                $ex
            );
        }
    }

    /**
     * Watch a given queue for jobs and execute them
     *
     * @param string $queue
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function watch($queue, $timeout = 5, $maxIterations = null, OutputInterface $output = null)
    {
        if (is_null($output)) {
            $output = new NullOutput();
        }
        $breakFile = $this->breakFile . '.' . $queue;

        if (!file_exists($breakFile)) {
            $output->writeln(
                sprintf('Creating breakfile at %s', $breakFile)
            );
            $contents = realpath(__FILE__);
            if (!file_put_contents($breakFile, $contents)) {
                throw new RuntimeException(
                    sprintf('Unable to create breakfile at %s', $breakFile)
                );
            }
        }

        $this->logger->debug('Starting queue watch', [
            'queue' => $queue
        ]);
        $output->writeln('Starting queue watch : ' . $queue);
        $this->connection->watch($queue);

        $iterations = 0;
        while (true) {
            clearstatcache(true, $breakFile);
            echo "ping\n";
            if (!file_exists($breakFile)) {
                $this->logger->notice('Queue worker exiting', [
                    'reason' => 'missing breakfile',
                    'break_file' => $breakFile,
                ]);
                $output->writeln('Exiting queue watch as breakfile is missing at ' . $breakFile);
                break;
            }
            $storedPath = trim(file_get_contents($breakFile));
            if (realpath(__FILE__) !== $storedPath) {
                $this->logger->notice('Queue worker exiting', [
                    'reason' => 'path mismatch',
                    'break_file' => $breakFile,
                    'file_path' => realpath(__FILE__),
                    'stored_path' => $storedPath,
                ]);
                $output->writeln('Exiting queue watch with mismatched file paths');
                break;
            }
            $this->logger->debug('Queue break file checks ok', [
                'break_file' => $breakFile,
                'file_path' => realpath(__FILE__),
                'stored_path' => $storedPath,
            ]);

            $queueJob = $this->connection->reserve($timeout);
            if (false === $queueJob) {
                continue;
            }
            $output->writeln('Reserved job : ' . $queueJob->getId());
            try {
                $data = $queueJob->getData();
                $data = unserialize($data);
                if (false == $data || !isset($data['class'], $data['args'])) {
                    throw new InvalidPayloadException('Invalid payload');
                }
                $class = $data['class'];
                $args = $data['args'];

                $output->writeln('Job class : ' . $class);
                $handler = new ClassJobHandler(
                    $class,
                    $args
                );
                if (false === $handler->handle()) {
                    throw new FailedJobException('Failed to handle job class ' . $class);
                }

                $output->writeln('Deleting job : ' . $queueJob->getId());
                $this->connection->delete($queueJob);
            } catch (FatalException $ex) {
                $this->logger->error('Fatal : ' . $ex->getMessage(), ['exception' => $ex]);
                $output->writeln('Burying job after fatal exception : ' . $queueJob->getId());
                $this->connection->bury($queueJob);
            } catch (Exception $ex) {
                $this->logger->error($ex->getMessage(), ['exception' => $ex]);
                $output->writeln('Releasing failed job : ' . $queueJob->getId());
                $this->connection->release($queueJob);
            }

            $iterations++;
            if (!is_null($maxIterations) && $iterations >= $maxIterations) {
                $output->writeln('Max iterations reached : ' . $iterations);
                break;
            }
        }
        $this->logger->debug('Finished queue watch', [
            'queue' => $queue
        ]);
        $output->writeln('Finished queue watch : ' . $queue);

        return true;
    }
}
