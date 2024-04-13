<?php

declare(strict_types=1);

namespace Iteo\Client\Domain\ValueObject\Order\Exception;

use Iteo\Client\Domain\ValueObject\Order\OrderItem\Quantity;

final class OrderSizeTooSmall extends \DomainException
{
    public function __construct(Quantity $clientSize, int $minOrderSize)
    {
        parent::__construct("Order size too small, $clientSize given, at least $minOrderSize required");
    }
}
