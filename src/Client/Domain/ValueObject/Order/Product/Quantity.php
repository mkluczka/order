<?php

declare(strict_types=1);

namespace Iteo\Client\Domain\ValueObject\Order\Product;

use Iteo\Client\Domain\ValueObject\Order\Product\Exception\QuantityMustByNonNegative;
use Stringable;

final readonly class Quantity implements Stringable
{
    public function __construct(public int $value)
    {
        if ($value < 0) {
            throw new QuantityMustByNonNegative($value);
        }
    }

    public function plus(self $other): self
    {
        return new self($this->value + $other->value);
    }

    public function isLessThen(int $other): bool
    {
        return $this->value < $other;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
