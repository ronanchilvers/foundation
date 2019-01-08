<?php

namespace Ronanchilvers\Foundation\Slim;

use Psr\Container\ContainerInterface;
use Slim\App as SlimApp;

/**
 * Slim application subclass providing a boot() mechanism
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
class App extends SlimApp
{
    /**
     * Override run() to provide a boot() mechanic
     *
     * @param boolean $silent
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function run($silent = false)
    {
        $this->boot($this->getContainer());
        return parent::run($silent);
    }

    /**
     * Boot the framework
     *
     * @param Psr\Container\ContainerInterface $container
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected function boot(ContainerInterface $container)
    {
    }
}
