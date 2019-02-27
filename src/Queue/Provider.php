<?php

namespace Ronanchilvers\Foundation\Queue;

use Pheanstalk\Pheanstalk;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Ronanchilvers\Foundation\Queue\Helper;

/**
 * Container provider for the queue
 *
 * @author Ronan Chilvers <ronan@d3r.com>
 */
class Provider implements ServiceProviderInterface
{
    /**
     * @author Ronan Chilvers <ronan@d3r.com>
     */
    public function register(Container $pimple)
    {
        $pimple['pheanstalk_connection'] = function ($container) {
            $config = $container['config']['queue.options'] ?? [];
            $config = array_merge([
                'host'       => 'localhost',
                'port'       => '11300',
                'timeout'    => null,
                'persistent' => false,
            ], $config);
            $connection = new Pheanstalk(
                $config['host'],
                $config['port'],
                $config['timeout'],
                $config['persistent']
            );

            return $connection;
        };

        $pimple['queue_helper'] = function ($container) {
            $logger = false;
            foreach (['logger', LoggerInterface::class] as $key) {
                if (isset($container[$key])) {
                    $logger = $container[$key];
                }
            }
            if (false == $logger) {
                $logger = new NullLogger();
            }
            $helper = new Helper(
                $logger,
                $container['pheanstalk_connection']
            );

            return $helper;
        };
    }
}
