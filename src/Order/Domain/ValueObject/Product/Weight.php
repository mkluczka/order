<?php

declare(strict_types=1);

namespace Iteo\Order\Domain\ValueObject\Product;

use Iteo\Shared\Decimal\Decimal;

final readonly class Weight implements \Stringable
{
    public function __construct(public Decimal $decimal)
    {
    }

    public static function fromFloat(float $value): self
    {
        return new self(Decimal::fromFloat($value));
    }

    public function asFloat(): float
    {
        return $this->decimal->asFloat();
    }

    public function __toString(): string
    {
        return (string) $this->decimal;
    }
}
