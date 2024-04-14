<?php

declare(strict_types=1);

namespace App\Infrastructure\Framework;

use Iteo\Shared\Queue\QueueBus;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\TransportNamesStamp;

final readonly class MessengerQueueBus implements QueueBus
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function dispatch(object $message): void
    {
        $this->messageBus->dispatch($message, [
            new TransportNamesStamp('queue')
        ]);
    }
}
