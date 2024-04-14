<?php

declare(strict_types=1);

namespace Iteo\Order\Domain\OrderState;

use Iteo\Shared\ClientId;
use Iteo\Shared\OrderId;

final readonly class OrderState
{
    public function __construct(
        public OrderId $orderId,
        public ClientId $clientId,
    ) {
    }
}
