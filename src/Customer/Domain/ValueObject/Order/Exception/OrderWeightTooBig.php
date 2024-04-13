<?php

declare(strict_types=1);

namespace Iteo\Customer\Domain\ValueObject\Order\Exception;

use Iteo\Customer\Domain\ValueObject\Order\OrderItem\Weight;

final class OrderWeightTooBig extends \DomainException
{
    public function __construct(Weight $orderWeight, float $maxWeight)
    {
        parent::__construct("Order weight is too big, $orderWeight give, max $maxWeight allowed");
    }
}
