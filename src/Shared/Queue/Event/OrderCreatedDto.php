<?php

declare(strict_types=1);

namespace Iteo\Shared\Queue\Event;

final readonly class OrderCreatedDto
{
    public function __construct(
        public string $orderId,
        public string $clientId,
        public float $orderPrice,
    ) {
    }
}
