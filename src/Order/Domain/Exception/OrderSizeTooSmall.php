<?php

declare(strict_types=1);

namespace Iteo\Order\Domain\Exception;

final class OrderSizeTooSmall extends \DomainException
{
    public function __construct(int $orderSize, int $minOrderSize)
    {
        parent::__construct("Order size too small, $orderSize given, at least $minOrderSize required");
    }
}
