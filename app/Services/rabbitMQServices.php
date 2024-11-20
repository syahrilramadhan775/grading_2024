<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class rabbitMQServices
{
    private $connections, $channels;

    public function __construct()
    {
        $this->connections = new AMQPStreamConnection(
            env('RABBITMQ_HOST'),
            env('RABBITMQ_PORT'),
            env('RABBITMQ_USER'),
            env('RABBITMQ_PASSWORD'),
            env('RABBITMQ_VHOST'),
        );

        $this->channels = $this->connections->channel();
    }

    public function sendMessages(string $queue, $messages)
    {
        $this->channels->queue_declare($queue, false, true, false, false);

        $message = new AMQPMessage($messages);

        $this->channels->basic_publish($message, '', $queue);
    }

    public function consumeMessages(string $queue, callable $callback)
    {
        $this->channels->queue_declare($queue, false, true, false, false);

        $this->channels->basic_consume($queue, '', false, false, false, false, $callback);

        while (count($this->channels->callbacks)) {
            $this->channels->wait(null, false, 7600);
        }
    }

    public function __destruct()
    {
        $this->channels->close();
        $this->connections->close();
    }
}