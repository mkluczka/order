<?php

declare(strict_types=1);

namespace Iteo\Order\Domain\Persistence;

use Iteo\Order\Domain\Order;
use Iteo\Shared\DomainEvent\DomainEventsDispatcher;

final readonly class OrderRepository
{
    public function __construct(
        private OrderStateRepository $orderStateRepository,
        private DomainEventsDispatcher $domainEventsDispatcher,
    ) {
    }

    public function save(Order $order): void
    {
        $this->orderStateRepository->save($order->save());

        $this->domainEventsDispatcher->dispatch(...$order->collectEvents());
    }
}
