<?php

declare(strict_types=1);

namespace Iteo\Client\Adapters\Queue;

use Iteo\Client\Application\Charge\ChargeClient;
use Iteo\Shared\CommandBus;
use Iteo\Shared\Queue\Event\OrderCreatedDto;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class OrderCreatedConsumer
{
    public function __construct(private CommandBus $commandBus)
    {
    }

    public function __invoke(OrderCreatedDto $event): void
    {
        $this->commandBus->dispatch(
            new ChargeClient(
                $event->clientId,
                $event->orderPrice,
            )
        );
    }
}
