<?php

namespace Ronanchilvers\Foundation\Tests\Psr\Traits;

use Ronanchilvers\Foundation\Psr\Traits\LoggerAwareTrait;
use Ronanchilvers\Foundation\Tests\TestCase;

/**
 * Test case for Ronanchilvers\Foundation\Psr\Traits\LoggerAwareTrait
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
class LoggerAwareTraitTest extends TestCase
{
    /**
     * Get a mock container instance
     *
     * @return Psr\Container\ContainerInterface
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected function mockLogger()
    {
        $builder = $this->getMockBuilder('Psr\Log\LoggerInterface');

        return $builder->getMock();
    }

    /**
     * Test that
     *
     * @test
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function testLoggerIsReturned()
    {
        $mockLogger = $this->mockLogger();
        $unit = $this->getMockBuilder(LoggerAwareTrait::class)->getMockForTrait();
        $unit->setLogger($mockLogger);

        $returnedLogger = $this->callProtectedMethod(
            $unit,
            'logger'
        );
        $this->assertEquals($mockLogger, $returnedLogger);
    }
}
