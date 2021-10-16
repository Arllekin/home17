<?php

declare(strict_types=1);

namespace App\Command;

use InvalidArgumentException;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Vitaliy Bilotil <bilotilvv@gmail.com>
 */
class PublishTaskCommand extends Command
{
    protected static $defaultName = 'hillel:task:publish';

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

    protected function configure(): void
    {
        $this->addArgument('message-body', InputArgument::REQUIRED, 'Message body');
        $this->addArgument('routing-key', InputArgument::OPTIONAL, 'Routing key', '');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $messageBody = $input->getArgument('message-body');
        $routingKey = (string) $input->getArgument('routing-key');
        if (empty($messageBody)) {
            throw new InvalidArgumentException('Argument "message-body" can not be empty');
        }

        $messageJson = json_encode(['body' => $messageBody]);

        $this->logger->info('Publishing message ...', ['message_raw' => $messageJson]);

        $this->producer->publish($messageJson, $routingKey);

        $this->logger->info('Message was published', ['message_raw' => $messageJson]);

        return 0;
    }
}
