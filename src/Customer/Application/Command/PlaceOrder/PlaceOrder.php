<?php

declare(strict_types=1);

namespace Iteo\Customer\Application\Command\PlaceOrder;

final readonly class PlaceOrder
{
    /**
     * @param string $orderId
     * @param string $customerId
     * @param array{productId: string, quantity: int, weight: float, price: float}[] $orderItems
     */
    public function __construct(
        public string $orderId,
        public string $customerId,
        public array $orderItems,
    ) {
    }
}
