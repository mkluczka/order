<?php

declare(strict_types=1);

namespace Iteo\Order\Infrastructure\EventSubscriber;

use Iteo\Order\Domain\Event\OrderCreated;
use Iteo\Shared\Queue\Event\OrderCreatedDto;
use Iteo\Shared\Queue\QueueBus;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final readonly class OrderCreatedQueueEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private QueueBus $queue)
    {
    }

    /**
     * @codeCoverageIgnore
     */
    public static function getSubscribedEvents(): array
    {
        return [
            OrderCreated::class => 'onOrderCreated',
        ];
    }

    public function onOrderCreated(OrderCreated $event): void
    {
        $this->queue->dispatch(
            new OrderCreatedDto(
                (string) $event->orderId,
                (string) $event->clientId,
                $event->orderPrice->asFloat(),
            ),
        );
    }
}
