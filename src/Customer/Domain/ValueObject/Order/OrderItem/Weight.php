<?php

declare(strict_types=1);

namespace Iteo\Customer\Domain\ValueObject\Order\OrderItem;

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

    public function multiplyBy(int $value): self
    {
        return new self($this->decimal->multiplyBy($value));
    }

    public function plus(self $other): self
    {
        return new self($this->decimal->plus($other->decimal));
    }

    public function isMoreThen(Decimal $other): bool
    {
        return $this->decimal->isMoreThen($other);
    }

    public function __toString(): string
    {
        return (string) $this->decimal;
    }
}
