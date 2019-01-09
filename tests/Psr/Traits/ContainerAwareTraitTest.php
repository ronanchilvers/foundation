<?php

namespace Ronanchilvers\Foundation\Tests\Psr\Traits;

use Ronanchilvers\Foundation\Psr\Traits\ContainerAwareTrait;
use Ronanchilvers\Foundation\Tests\TestCase;

/**
 * Test case for Ronanchilvers\Foundation\Psr\Traits\ContainerAwareTrait
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
class ContainerAwareTraitTest extends TestCase
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

        return $builder->getMock();
    }

    /**
     * Test that
     *
     * @test
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function testContainerIsReturned()
    {
        $mockContainer = $this->mockContainer();
        $unit = $this->getMockBuilder(ContainerAwareTrait::class)->getMockForTrait();
        $unit->setContainer($mockContainer);

        $returnedContainer = $this->callProtectedMethod(
            $unit,
            'container'
        );
        $this->assertEquals($mockContainer, $returnedContainer);
    }
}
