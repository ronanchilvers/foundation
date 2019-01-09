<?php

namespace Ronanchilvers\Foundation\Psr\Traits;

use Psr\Log\LoggerAwareTrait as PsrLoggerAwareTrait;
use Psr\Log\LoggerInterface;

/**
 * Trait for objects that are logger aware
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
trait LoggerAwareTrait
{
    use PsrLoggerAwareTrait;

    /**
     * Get the Logger object
     *
     * @return Psr\Log\LoggerInterface
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected function logger(): LoggerInterface
    {
        return $this->logger;
    }
}
