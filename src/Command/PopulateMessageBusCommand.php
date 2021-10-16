<?php

declare(strict_types=1);

namespace App\Command;

use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Vitaliy Bilotil <bilotilvv@gmail.com>
 */
class PopulateMessageBusCommand extends Command
{
    protected static $defaultName = 'hillel:message-bus:populate';

    /** @var ProducerInterface */
    private $producer;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(ProducerInterface $producer, LoggerInterface $logger)
    {
        parent::__construct();

        $this->producer = $producer;
        $this->logger = $logger;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->logger->info('Publishing messages ...');

        $this->producer->publish('{"product_id":622,"price":92.49}', 'event.product.created');
        $this->producer->publish('{"product_id":771,"values":{"price":215.99},"previous_values":{"price":209.99}}', 'event.product.updated');
        $this->producer->publish('{"product_id":605,"values":{"qoh":10},"previous_values":{"qoh":60}}', 'event.product.updated');
        $this->producer->publish('{"product_id":58,"values":{"qoh":4},"previous_values":{"qoh":12}}', 'event.product.updated');
        $this->producer->publish('{"product_id":741,"values":{"qoh":0},"previous_values":{"qoh":4}}', 'event.product.updated');
        $this->producer->publish('{"product_id":51,"values":{"qoh":5},"previous_values":{"qoh":6}}', 'event.product.updated');
        $this->producer->publish('{"product_id":149,"values":{"qoh":50},"previous_values":{"qoh":0}}', 'event.product.updated');
        $this->producer->publish('{"product_id":467}', 'event.product.deleted');
        $this->producer->publish('{"brand_id":350,"name":"12 Survivors"}', 'event.brand.created');
        $this->producer->publish('{"brand_id":558,"values":{"name":"Bushnell"},"previous_values":{"name":"Bushnell X"}}', 'event.brand.updated');
        $this->producer->publish('{"brand_id":22}', 'event.brand.deleted');
        $this->producer->publish('{"message":"New product was added","context":{"product_id":622}}', 'log.debug');
        $this->producer->publish('{"message":"Product was updated","context":{"product_id":771}}', 'log.info');
        $this->producer->publish('{"message":"Can not delete brand","context":{"brand_id":584}}', 'log.error');

        $this->logger->info('Messages were published');

        return 0;
    }
}
