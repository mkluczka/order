<?php

declare(strict_types=1);

namespace Iteo\Client\Domain\ValueObject\Order\Exception;

use Iteo\Client\Domain\ValueObject\Order\OrderItem\Weight;

final class OrderWeightTooBig extends \DomainException
{
    public function __construct(Weight $clientWeight, float $maxWeight)
    {
        parent::__construct("Order weight is too big, $clientWeight give, max $maxWeight allowed");
    }
}
