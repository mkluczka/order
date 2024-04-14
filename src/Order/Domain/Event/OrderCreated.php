<?php

declare(strict_types=1);

namespace Iteo\Order\Domain\Event;

use Iteo\Order\Domain\ValueObject\Product\Product;
use Iteo\Shared\ClientId;
use Iteo\Shared\DomainEvent\DomainEvent;
use Iteo\Shared\Money\Money;
use Iteo\Shared\OrderId;

final readonly class OrderCreated implements DomainEvent
{
    /**
     * @param array<Product> $products
     */
    public function __construct(
        public OrderId $orderId,
        public ClientId $clientId,
        public Money $orderPrice,
        public array $products,
    ) {
    }
}
