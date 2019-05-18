<?php

namespace Ronanchilvers\Foundation\Queue;

use Pheanstalk\Pheanstalk;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Ronanchilvers\Container\Container;
use Ronanchilvers\Container\ServiceProviderInterface;
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
    public function register(Container $container)
    {
        $container->set('pheanstalk_settings', []);

        $container->set('pheanstalk_connection', function ($container) {
            $config = $container->get('pheanstalk_settings');
            $config = array_merge([
                'host'       => '127.0.0.1',
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
        });

        $container->set('queue_helper', function ($container) {
            $logger = false;
            foreach ([LoggerInterface::class, 'logger', 'monolog'] as $key) {
                if ($container->has($key)) {
                    $logger = $container->get($key);
                }
            }
            if (false == $logger) {
                $logger = new NullLogger();
            }
            $helper = new Helper(
                $logger,
                $container->get('pheanstalk_connection')
            );

            return $helper;
        });
    }
}
