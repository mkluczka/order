<?php

declare(strict_types=1);

namespace Iteo\Customer\Domain\ValueObject\Order;

final readonly class OrderId
{
    public function __construct(public string $value)
    {
    }
}
