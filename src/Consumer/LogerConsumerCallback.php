<?php

namespace App\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

class LogerConsumerCallback implements ConsumerInterface
{
    /** @var LoggerInterface */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return int|bool One of ConsumerInterface::MSG_* constants according to callback outcome, or false otherwise.
     */
    public function execute(AMQPMessage $msg)
    {
        $this->logger->log(str_replace('log.', '', $msg->getRoutingKey()),
            'Received message',
            [
                'message_body_raw' => $msg->getBody(),
                'routing_key' => $msg->getRoutingKey(),
            ]
        );

        return ConsumerInterface::MSG_ACK;
    }
}