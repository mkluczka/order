<?php

declare(strict_types=1);

namespace Iteo\Client\Adapters\Queue;

use Iteo\Client\Application\Block\Block;
use Iteo\Shared\CommandBus;
use Iteo\Shared\Queue\Event\ClientBlockedDto;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class ClientBlockedConsumer
{
    public function __construct(private CommandBus $commandBus)
    {
    }

    public function __invoke(ClientBlockedDto $event): void
    {
        $this->commandBus->dispatch(
            new Block(
                $event->clientId
            )
        );
    }
}
