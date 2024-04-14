<?php

declare(strict_types=1);

namespace Iteo\Order\Domain\Specification;

use Iteo\Shared\OrderId;

interface OrderIdWasUsed
{
    public function isSatisfiedBy(OrderId $orderId): bool;
}
