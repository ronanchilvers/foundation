<?php

namespace Ronanchilvers\Foundation\Queue\Job;

/**
 * Interface for queue jobs
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
interface DispatchableInterface
{
    /**
     * Get the class for this dispatchable
     *
     * @return string
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function getClass();

    /**
     * Get the arguments for this job
     *
     * @return array
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function getArgs();

    /**
     * Get the delay for this job
     *
     * @return Carbon\Carbon
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function getDelay();

    /**
     * Get the queue name for this job
     *
     * @return string
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function getQueue();
}
