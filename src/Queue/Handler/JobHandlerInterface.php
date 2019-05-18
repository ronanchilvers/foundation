<?php

namespace Ronanchilvers\Foundation\Queue\Handler;

/**
 * Interface for job handler classes
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
interface JobHandlerInterface
{
    /**
     * Handle the task
     *
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function handle();
}
