<?php

declare(strict_types=1);

namespace Iteo\Customer\Domain\Specification;

use Iteo\Customer\Domain\ValueObject\Order\OrderId;

interface OrderIdWasUsed
{
    public function isSatisfiedBy(OrderId $orderId): bool;
}
