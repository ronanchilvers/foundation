<?php

namespace Ronanchilvers\Foundation\Facade;

use Psr\Container\ContainerInterface;
use RuntimeException;

/**
 * Base facade class
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
abstract class Facade
{
    /**
     * @var string
     */
    protected static $serviceName;

    /**
     * @var Psr\Container\ContainerInterface
     */
    protected static $container;

    /**
     * Set the facade application
     *
     * @param Psr\Container\ContainerInterface $container
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public static function setContainer(ContainerInterface $container)
    {
        self::$container = $container;
    }

    /**
     * Get the facade application
     *
     * @return Psr\Container\ContainerInterface
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public static function getContainer()
    {
        return self::$container;
    }

    /**
     * Get the facade name
     *
     * @return string
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected static function getFacadeName()
    {
        if (is_null(static::$serviceName)) {
            throw new RuntimeException('Facade has not set a name - please set static::$serviceName');
        }

        return static::$serviceName;
    }

    /**
     * Get the service instance for this facade
     *
     * @return mixed
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public static function getService()
    {
        $name = static::getFacadeName();

        return self::$container->get($name);
    }

    /**
     * Magic static call
     *
     * @param string $method
     * @param array $args
     * @return mixed
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public static function __callStatic($method, $args)
    {
        $name = static::getFacadeName();
        if (!self::$container->has($name)) {
            throw new RuntimeException(sprintf('Facade service %s is unknown in the container', $name));
        }
        $instance = static::getService();

        return call_user_func_array([$instance, $method], $args);
    }
}
