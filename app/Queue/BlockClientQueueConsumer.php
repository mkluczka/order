<?php

declare(strict_types=1);

namespace App\Queue;

use Iteo\Client\Application\Command\BlockClient\BlockClient;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final readonly class BlockClientQueueConsumer
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function __invoke(BlockClientDto $queueMessage): void
    {
        $this->messageBus->dispatch(new BlockClient($queueMessage->clientId));
    }
}
