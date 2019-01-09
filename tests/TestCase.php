<?php

namespace Ronanchilvers\Foundation\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Psr\Container\ContainerInterface;
use ReflectionClass;

abstract class TestCase extends PHPUnitTestCase
{
    /**
     * Get a protected method for later invocation
     *
     * @return \ReflectionMethod
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected function getProtectedMethod($object, $method)
    {
        $reflection = new ReflectionClass($object);
        $method = $reflection->getMethod($method);
        $method->setAccessible(true);

        return $method;
    }

    /**
     * Call a protected method on a given object
     *
     * @param object $object
     * @param string $method
     * @param array $params
     * @return mixed
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected function callProtectedMethod($object, $method, ...$params)
    {
        $method = $this->getProtectedMethod($object, $method);

        return $method->invokeArgs($object, $params);
    }

    /**
     * Get the value of a protected property
     *
     * @return mixed
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected function getProtectedProperty($object, $property)
    {
        $reflection = new ReflectionClass($object);
        $property = $reflection->getProperty($property);
        $property->setAccessible(true);

        return $property->getValue($object);
    }
}
