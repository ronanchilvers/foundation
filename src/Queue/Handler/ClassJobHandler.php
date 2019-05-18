<?php

namespace Ronanchilvers\Foundation\Queue\Handler;

use ReflectionClass;

/**
 * Job handler for job classes
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
class ClassJobHandler implements JobHandlerInterface
{
    /**
     * @var string
     */
    protected $class;

    /**
     * @var $constructorArgs
     */
    protected $constructorArgs;

    /**
     * Class constructor
     *
     * @param string $class
     * @param string $constructorArgs
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function __construct($class, $constructorArgs)
    {
        $this->class           = $class;
        $this->constructorArgs = $constructorArgs;
    }

    /**
     * {@inheritdoc}
     *
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function handle()
    {
        $reflection = new ReflectionClass(
            $this->class
        );
        $instance = $reflection->newInstanceArgs(
            $this->constructorArgs
        );

        return $instance->execute();
    }
}
