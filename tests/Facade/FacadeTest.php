<?php

namespace Ronanchilvers\Foundation\Tests\Facade;

use Ronanchilvers\Foundation\Facade\Facade;
use Ronanchilvers\Foundation\Tests\Facade\MockFacade;
use Ronanchilvers\Foundation\Tests\TestCase;
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
    protected function mockContainer()
    {
        $builder = $this->getMockBuilder('Psr\Container\ContainerInterface')
                     ->setMethods(['get', 'has']);
        $mock = $builder->getMock();
        $mock->expects($this->any())
             ->method('has')
             ->with(MockFacade::MOCK_SERVICE_NAME)
             ->willReturn(true);
        $mock->expects($this->any())
             ->method('get')
             ->with(MockFacade::MOCK_SERVICE_NAME)
             ->willReturn(new StdClass);

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
        $mockContainer = $this->mockContainer();
        Facade::setContainer(
            $mockContainer
        );

        $this->assertEquals(
            $mockContainer,
            MockFacade::getContainer()
        );
    }

    /**
     * Test that
     *
     * @test
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function testFacadeNameIsCorrect()
    {
        $resolvedName = $this->callProtectedMethod(
            MockFacade::class,
            'getFacadeName'
        );

        $this->assertEquals(
            MockFacade::MOCK_SERVICE_NAME,
            $resolvedName
        );
    }
}
