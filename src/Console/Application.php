<?php

namespace Ronanchilvers\Foundation\Console;

use Psr\Container\ContainerInterface;
use Ronanchilvers\Foundation\Psr\Traits\ContainerAwareTrait;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Base console application
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
class Application extends BaseApplication
{
    use ContainerAwareTrait;

    /**
     * {@inheritdoc}
     *
     * Overridden to provide a boot method
     *
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        $this->boot($this->container);

        return parent::run($input, $output);
    }

    /**
     * Get the container object
     *
     * @return Psr\Container\ContainerInterface
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Boot the application
     *
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected function boot(ContainerInterface $container)
    {
    }
}
