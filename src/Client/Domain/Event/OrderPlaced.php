<?php

declare(strict_types=1);

namespace Iteo\Client\Domain\Event;

use Iteo\Client\Domain\ValueObject\ClientId;
use Iteo\Client\Domain\ValueObject\Order\OrderId;
use Iteo\Client\Domain\ValueObject\Order\Product\Product;
use Iteo\Shared\DomainEvent\DomainEvent;

final readonly class OrderPlaced implements DomainEvent
{
    /**
     * @param array<Product> $products
     */
    public function __construct(
        public OrderId $orderId,
        public ClientId $clientId,
        public array $products,
    ) {
    }
}
