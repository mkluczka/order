<?php

declare(strict_types=1);

namespace Iteo\Customer\Domain\Event;

use Iteo\Customer\Domain\ValueObject\CustomerId;
use Iteo\Customer\Domain\ValueObject\Order\OrderId;
use Iteo\Customer\Domain\ValueObject\Order\OrderItem\OrderItem;
use Iteo\Shared\DomainEvent\DomainEvent;

final readonly class OrderPlaced implements DomainEvent
{
    /**
     * @param array<OrderItem> $orderItems
     */
    public function __construct(
        public OrderId $orderId,
        public CustomerId $customerId,
        public array $orderItems,
    ) {
    }
}
