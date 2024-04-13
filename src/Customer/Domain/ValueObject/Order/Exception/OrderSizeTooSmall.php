<?php

declare(strict_types=1);

namespace Iteo\Customer\Domain\ValueObject\Order\Exception;

use Iteo\Customer\Domain\ValueObject\Order\OrderItem\Quantity;

final class OrderSizeTooSmall extends \DomainException
{
    public function __construct(Quantity $orderSize, int $minOrderSize)
    {
        parent::__construct("Order size too small, $orderSize given, at least $minOrderSize required");
    }
}
