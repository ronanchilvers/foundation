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
        $container->set('helper_settings', []);

        $container->set('pheanstalk_connection', function ($container) {
            $config = $container->get('pheanstalk_settings');
            $config = array_merge([
                'host'       => '127.0.0.1',
                'port'       => '11300',
                'timeout'    => null,
                'persistent' => false,
            ], $config);
            $connection = Pheanstalk::create(
                $config['host'],
                $config['port'],
                $config['timeout']
            );

            return $connection;
        });

        $container->set('queue_helper', function ($container) {
            $config = $container->get('helper_settings');
            $logger = false;
            foreach ([LoggerInterface::class, 'logger', 'monolog'] as $key) {
                if ($container->has($key)) {
                    $logger = $container->get($key);
                }
            }
            if (false == $logger) {
                $logger = new NullLogger();
            }
            if (!isset($config['breakfile'])) {
                $dir = __DIR__ . '/../../../../../var/cache';
                if (is_dir($dir)) {
                    $config['breakfile'] = $dir . '/queue.lock';
                }
            }
            $helper = new Helper(
                $logger,
                $container->get('pheanstalk_connection'),
                $config['breakfile']
            );

            return $helper;
        });
    }
}
