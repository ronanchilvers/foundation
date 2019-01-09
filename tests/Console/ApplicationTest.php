<?php

namespace Ronanchilvers\Foundation\Tests\Console;

use Ronanchilvers\Foundation\Console\Application;
use Ronanchilvers\Foundation\Tests\TestCase;

/**
 * Test case for symfony console application subclass
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
class ApplicationTest extends TestCase
{
    /**
     * Get a mock container instance
     *
     * @return Psr\Container\ContainerInterface
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected function mockContainer($serviceName = 'foobar')
    {
        $builder = $this->getMockBuilder('Psr\Container\ContainerInterface');

        return $builder->getMock();
    }

    /**
     * Test that we can obtain the container from the application object
     *
     * @group current
     * @test
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function testGettingContainerFromApplication()
    {
        $mockContainer = $this->mockContainer();
        $unit = new Application();
        $unit->setContainer($mockContainer);

        $this->assertSame(
            $mockContainer,
            $unit->getContainer()
        );
    }
}
