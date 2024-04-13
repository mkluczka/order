<?php

declare(strict_types=1);

namespace Iteo\Client\Domain\ValueObject\Order\OrderItem\Exception;

final class QuantityMustByNonNegative extends \DomainException
{
    public function __construct(int $quantity)
    {
        parent::__construct("Quantity must be non-negative, $quantity given");
    }
}
