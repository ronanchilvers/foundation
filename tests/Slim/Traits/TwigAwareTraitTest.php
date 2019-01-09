<?php

namespace Ronanchilvers\Foundation\Slim\Traits;

use Psr\Http\Message\ResponseInterface;
use Ronanchilvers\Foundation\Slim\Traits\TwigAwareTrait;
use Ronanchilvers\Foundation\Tests\TestCase;
use Slim\Views\Twig;

/**
 * Test case for twig aware trait
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
class TwigAwareTraitTest extends TestCase
{
    /**
     * Get a mock Twig view object
     *
     * @return Slim\Views\Twig
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected function mockTwig()
    {
        $builder = $this->getMockbuilder(Twig::class);
        $builder->disableOriginalConstructor();

        return $builder->getMock();
    }

    /**
     * Test that we can set and retrieve a twig object correctly
     *
     * @test
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function testSettingAndRetrievingTwigObject()
    {
        $mockTwig = $this->mockTwig();
        $unit = $this->getMockBuilder(TwigAwareTrait::class)
                ->getMockForTrait();
        $unit->setTwig($mockTwig);

        $this->assertSame(
            $mockTwig,
            $this->callProtectedMethod($unit, 'twig')
        );
    }

    /**
     * Test that render is called correctly on the twig object
     *
     * @test
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function testRenderIsCalledCorrectlyOnTwig()
    {
        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockTwig = $this->mockTwig();
        $mockTwig->expects($this->once())
                 ->method('render')
                 ->with($mockResponse, 'template', [])
                 ->willReturn($mockResponse);
        $unit = $this->getMockBuilder(TwigAwareTrait::class)
                ->getMockForTrait();
        $unit->setTwig($mockTwig);

        $this->assertSame(
            $mockResponse,
            $this->callProtectedMethod(
                $unit,
                'render',
                $mockResponse,
                'template',
                []
            )
        );
    }
}
