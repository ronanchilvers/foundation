<?php

namespace Ronanchilvers\Foundation\Queue;

/**
 * Interface for queue jobs
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
interface JobInterface
{
    /**
     * Execute this job
     *
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function execute();
}
