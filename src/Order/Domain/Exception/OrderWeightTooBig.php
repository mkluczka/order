<?php

declare(strict_types=1);

namespace Iteo\Order\Domain\Exception;

final class OrderWeightTooBig extends \DomainException
{
    public function __construct(float $orderWeight, float $maxWeight)
    {
        parent::__construct("Order weight is too big, $orderWeight give, max $maxWeight allowed");
    }
}
