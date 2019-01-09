<?php

namespace Ronanchilvers\Foundation\Tests\Facade;

use Ronanchilvers\Foundation\Facade\Facade;
use Ronanchilvers\Foundation\Tests\TestCase;
use RuntimeException;
use StdClass;

/**
 * Test case for Ronanchilvers\Foundation\Facade\Facade
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
class FacadeTest extends TestCase
{
    /**
     * Get a mock container instance
     *
     * @return Psr\Container\ContainerInterface
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected function mockContainer($serviceName = 'foobar')
    {
        $builder = $this->getMockBuilder('Psr\Container\ContainerInterface')
                     ->setMethods(['get', 'has']);
        $mock = $builder->getMock();
        $mock->expects($this->any())
             ->method('has')
             ->with($serviceName)
             ->willReturn(true);
        $mock->expects($this->any())
             ->method('get')
             ->with($serviceName)
             ->willReturn(new class {
                public function ahem() {
                    return 'cough';
                }
             });
        return $mock;
    }

    /**
     * Test that container is maintained in facades
     *
     * @test
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function testFacadeHasCorrectContainer()
    {
        $mockContainer = $this->mockContainer('foobar');
        Facade::setContainer(
            $mockContainer
        );
        $class = new class extends Facade {};

        $this->assertEquals(
            $mockContainer,
            $class::getContainer()
        );
    }

    /**
     * Test that facades without a name throw exceptions
     *
     * @test
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function testFacadeWithNoNameThrowsException()
    {
        $this->expectException(RuntimeException::class);
        $class = new class extends Facade {};

        $class::getFacadeName();
    }

    /**
     * Test that an unknown service name throws exceptions
     *
     * @test
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function testFacadeWithInvalidServiceNameThrowsException()
    {
        $this->expectException(RuntimeException::class);
        $class = new class extends Facade {
            static protected $serviceName = 'jumble';
        };

        $class::ahem();
    }

    /**
     * Test that
     *
     * @test
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function testFacadeNameIsCorrect()
    {
        $class = new class extends Facade {
            static protected $serviceName = 'foobar';
        };
        $resolvedName = $this->callProtectedMethod(
            $class,
            'getFacadeName'
        );

        $this->assertEquals(
            'foobar',
            $resolvedName
        );
    }

    /**
     * Test that we can get the service from the facade
     *
     * @test
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function testFacadeCanReturnTheService()
    {
        $mockContainer = $this->mockContainer('foobar');
        Facade::setContainer(
            $mockContainer
        );
        $class = new class extends Facade {
            static protected $serviceName = 'foobar';
        };
        $containerService = $mockContainer->get('foobar');
        $service = $class::getService();

        $this->assertSame(
            $service,
            $containerService
        );
    }

    /**
     * Test that we can call a method on the service via the facade
     *
     * @test
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function testServiceMethodsCanBeCalledViaFacade()
    {
        $mockContainer = $this->mockContainer('foobar');
        Facade::setContainer(
            $mockContainer
        );
        $class = new class extends Facade {
            protected static $serviceName = 'foobar';
        };
        $result = $class::ahem();

        $this->assertSame(
            $result,
            'cough'
        );
    }
}
