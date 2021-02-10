<?php

namespace App\Bschmitt\Amqp;

use Closure;
use Exception;

/**
 * @author Björn Schmitt <code@bjoern.io>
 */
class Amqp
{
    /**
     * @param string $routing
     * @param mixed $message
     * @param array $properties
     * @throws Exception
     */
    public function publish($routing, $message, array $properties = [])
    {
        $properties['routing'] = $routing;

        /* @var Publisher $publisher */
        $publisher = app()->make(Publisher::class);
        $publisher
            ->mergeProperties($properties)
            ->setup();

        if (is_string($message)) {
            $message = new Message($message, ['content_type' => 'text/plain', 'delivery_mode' => 2]);
        }

        $publisher->publish($routing, $message);
        Request::shutdown($publisher->getChannel(), $publisher->getConnection());
    }

    /**
     * @param string $queue
     * @param Closure $callback
     * @param array $properties
     * @throws Exception
     */
    public function consume($queue, Closure $callback, $properties = [])
    {
        $properties['queue'] = $queue;

        /* @var Consumer $consumer */
        $consumer = app()->make(Consumer::class);
        $consumer
            ->mergeProperties($properties)
            ->setup();

        $consumer->consume($queue, $callback);
        Request::shutdown($consumer->getChannel(), $consumer->getConnection());
    }

    /**
     * @param string $body
     * @param array  $properties
     * @return Message
     */
    public function message($body, $properties = [])
    {
        return new Message($body, $properties);
    }
}
