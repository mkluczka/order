<?php

declare(strict_types=1);

namespace Iteo\Client\Domain\ValueObject\Order\OrderItem;

final readonly class ProductId
{
    public function __construct(public string $value)
    {
    }
}
