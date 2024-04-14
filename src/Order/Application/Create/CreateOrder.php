<?php

declare(strict_types=1);

namespace Iteo\Order\Application\Create;

final readonly class CreateOrder
{
    /**
     * @param string $orderId
     * @param string $clientId
     * @param array{productId: string, quantity: int, weight: float, price: float}[] $products
     */
    public function __construct(
        public string $orderId,
        public string $clientId,
        public array $products,
    ) {
    }
}
