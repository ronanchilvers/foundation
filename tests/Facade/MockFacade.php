<?php

namespace Ronanchilvers\Foundation\Tests\Facade;

use Ronanchilvers\Foundation\Facade\Facade;

/**
 * Mock facade for testing
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
class MockFacade extends Facade
{
    const MOCK_SERVICE_NAME = 'foobar';

    /**
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    protected static function getFacadeName()
    {
        return static::MOCK_SERVICE_NAME;
    }
}
