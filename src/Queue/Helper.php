<?php

namespace Ronanchilvers\Foundation\Queue;

use Carbon\Carbon;
use Exception;
use Pheanstalk\Pheanstalk;
use Pheanstalk\PheanstalkInterface;
use Ronanchilvers\Foundation\Queue\ClassJobHandler;
use Ronanchilvers\Foundation\Queue\FailedJobException;
use Ronanchilvers\Foundation\Queue\InvalidPayloadException;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Helper for queue operations
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
class Helper
{
    /**
     * @var Pheanstalk\Connection
     */
    protected $connection;

    /**
     * Class constructor
     *
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function __construct(Pheanstalk $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Dispatch a job to the queue
     *
     * @param App\Queue\JobInterface $job
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

        return $this->connection
            ->useTube($queue)
            ->put(
                serialize($data),
                PheanstalkInterface::DEFAULT_PRIORITY,
                $delay
            );
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
        $output->writeln('Starting queue watch : ' . $queue);
        $this->connection->watch($queue);

        $iterations = 0;
        while (true) {
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
            // } catch (FatalException $ex) {
            //     Log::error('Fatal : ' . $ex->getMessage(), ['exception' => $ex]);
            //     $output->writeln('Burying job after fatal exception : ' . $queueJob->getId());
            //     $this->connection->bury($queueJob);
            } catch (Exception $ex) {
                Log::error($ex->getMessage(), ['exception' => $ex]);
                $output->writeln('Releasing failed job : ' . $queueJob->getId());
                $this->connection->release($queueJob);
            }

            $iterations++;
            if (!is_null($maxIterations) && $iterations >= $maxIterations) {
                $output->writeln('Max iterations reached : ' . $iterations);
                break;
            }
        }
        $output->writeln('Finished queue watch : ' . $queue);

        return true;
    }
}
