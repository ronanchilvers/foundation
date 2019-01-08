<?php

namespace Ronanchilvers\Foundation\Psr\Traits;

use Psr\Container\ContainerInterface;

/**
 * Trait for objects that are container aware
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
trait ContainerAwareTrait
{
    /**
     * @var Psr\Container\ContainerInterface
     */
    private $container;

    /**
     * Set the container
     *
     * @param Psr\Container\ContainerInterface $container
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Get the container object
     *
     * @return Psr\Container\ContainerInterface
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected function container()
    {
        return $this->container;
    }
}
