<?php

namespace Ronanchilvers\Foundation\Queue\Job;

use App\Queue\PendingDispatch;
use App\Utility\Facades\Queue;
use Carbon\Carbon;
use ReflectionClass;
use Ronanchilvers\Foundation\Queue\Job\DispatchableInterface;
use Ronanchilvers\Foundation\Queue\Job\JobInterface;

/**
 * Class representing a queue job
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
abstract class Job implements
    JobInterface,
    DispatchableInterface
{
    /**
     * @var Carbon\Carbon
     */
    protected $delay = 0;

    /**
     * @var string
     */
    protected $queue = 'default';

    /**
     * Get the job class
     *
     * @return string
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function getClass()
    {
        return get_class($this);
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function getArgs()
    {
        $properties = get_object_vars($this);
        foreach (['delay', 'queue'] as $prop) {
            unset($properties[$prop]);
        }

        return $properties;
    }

    /**
     * Delay this job by a given time interval
     *
     * @param Carbon\Carbon $carbon
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function delay(Carbon $carbon)
    {
        $this->delay = $carbon;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return Carbon\Carbon
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function getDelay()
    {
        return $this->delay;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * Set the queue for this job
     *
     * @param string $name
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function onQueue($name)
    {
        $this->queue = $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    abstract public function execute();
}
