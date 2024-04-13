<?php

declare(strict_types=1);

namespace Iteo\Client\Domain\Specification;

use Iteo\Client\Domain\ValueObject\Order\OrderId;

interface OrderIdWasUsed
{
    public function isSatisfiedBy(OrderId $orderId): bool;
}
