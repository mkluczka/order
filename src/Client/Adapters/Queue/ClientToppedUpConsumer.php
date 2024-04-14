<?php

declare(strict_types=1);

namespace Iteo\Client\Adapters\Queue;

use Iteo\Client\Application\TopUp\TopUp;
use Iteo\Shared\CommandBus;
use Iteo\Shared\Queue\Event\ClientToppedUpDto;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class ClientToppedUpConsumer
{
    public function __construct(private CommandBus $commandBus)
    {
    }

    public function __invoke(ClientToppedUpDto $event): void
    {
        $this->commandBus->dispatch(
            new TopUp(
                $event->clientId,
                $event->additionalAmount,
            )
        );
    }
}
