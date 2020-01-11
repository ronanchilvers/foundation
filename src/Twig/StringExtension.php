<?php

namespace Ronanchilvers\Foundation\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Utility extension with some handy string methods
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
class StringExtension extends AbstractExtension
{
    /**
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function getFunctions()
    {
        return [
            new TwigFunction(
                'plural',
                '\Ronanchilvers\Utility\Str::plural'
            ),
            new TwigFunction(
                'singular',
                '\Ronanchilvers\Utility\Str::singular'
            ),
            new TwigFunction(
                'pascal',
                '\Ronanchilvers\Utility\Str::pascal'
            ),
            new TwigFunction(
                'camel',
                '\Ronanchilvers\Utility\Str::camel'
            ),
            new TwigFunction(
                'snake',
                '\Ronanchilvers\Utility\Str::snake'
            ),
            new TwigFunction(
                'truncate',
                '\Ronanchilvers\Utility\Str::truncate'
            ),
        ];
    }

}
