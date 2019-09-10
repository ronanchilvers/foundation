<?php

namespace Ronanchilvers\Foundation\Filesystem;

/**
 * Registry for filesystem disks
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
class DiskRegistry
{
    /**
     * @var array
     */
    protected $instances;

    /**
     * Set an instance into the registry
     *
     * @param string $identifier
     * @param mixed $instance
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function addInstance($identifier, $instance)
    {
        $this->instances[$identifier] = $instance;
    }

    /**
     * Get an instance from the registry
     *
     * @param string $instance
     * @return mixed
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function getInstance($identifier)
    {
        if (!isset($this->instances[$identifier])) {
            throw new \RuntimeException('Unknown identifier ' . $identifier);
        }

        return $this->instances[$identifier];
    }

    /**
     * Get a filesystem disk
     *
     * @param string $identifier
     * @return App\Filesystem\Disk
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function disk($identifier)
    {
        return $this->getInstance($identifier);
    }
}
