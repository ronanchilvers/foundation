<?php

namespace Ronanchilvers\Foundation\Slim\Traits;

use Slim\Views\Twig;

/**
 * Trait for objects that are Twig aware
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
trait TwigAwareTrait
{
    /**
     * @var Psr\Log\TwigInterface
     */
    private $twig;

    /**
     * Set the Twig
     *
     * @param Slim\Views\Twig $twig
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function setTwig(Twig $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Get the Twig object
     *
     * @return Slim\Views\Twig
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected function twig()
    {
        return $this->twig;
    }

    /**
     * Render a template
     *
     * @param string $template
     * @param array $data
     * @return Psr\Http\Message\ResponseInterface
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected function render($response, $template, $data = [])
    {
        return $this->twig()->render(
            $response,
            $template,
            $data
        );
    }
}
