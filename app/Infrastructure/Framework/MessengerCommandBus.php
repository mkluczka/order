<?php

declare(strict_types=1);

namespace App\Infrastructure\Framework;

use Iteo\Shared\CommandBus;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class MessengerCommandBus implements CommandBus
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function dispatch(object $command): void
    {
        $this->messageBus->dispatch($command);
    }
}
