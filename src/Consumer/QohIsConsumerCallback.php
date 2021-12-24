<?php

declare(strict_types=1);

namespace App\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

/**
 * @author Vitaliy Bilotil <bilotilvv@gmail.com>
 */
class QohIsConsumerCallback implements ConsumerInterface
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
        $body = json_decode($msg->getBody(), true);
            $this->logger->info(
                'Received message',
                [
                    'message_body_raw' => $body["product_id"],
                    'routing_key'      => $msg->getRoutingKey(),
                ]
            );

        if($body["previous_values"]["qoh"] >= 5 && $body["values"]["qoh"] < 5) {
            mail('stock@example.com', 'subject',  'qoh for an item with id'
                . $body["product_id"]
                . 'dips below 5, with the current qoh' );
        }

        return ConsumerInterface::MSG_ACK;
    }
};
