<?php

declare(strict_types=1);

namespace Iteo\Shared\Decimal;

use Stringable;

final readonly class Decimal implements Stringable
{
    private function __construct(private int $decimalAmount)
    {
    }

    public static function fromFloat(float $value): self
    {
        $rounded = round($value, 2);
        if ($rounded !== $value) {
            throw new InvalidDecimalFormat($value);
        }

        return new self((int) ($rounded * 100));
    }

    public function asFloat(): float
    {
        return $this->decimalAmount / 100;
    }

    public function multiplyBy(int $value): self
    {
        return new self($this->decimalAmount * $value);
    }

    public function plus(self $other): self
    {
        return new self($this->decimalAmount + $other->decimalAmount);
    }

    public function isMoreThen(self $other): bool
    {
        return $this->decimalAmount > $other->decimalAmount;
    }

    public function isLessThen(self $other): bool
    {
        return $this->decimalAmount < $other->decimalAmount;
    }

    public function minus(self $other): self
    {
        return new self($this->decimalAmount - $other->decimalAmount);
    }

    public function equals(self $other): bool
    {
        return $this->decimalAmount === $other->decimalAmount;
    }

    public function __toString(): string
    {
        return number_format($this->asFloat(), 2);
    }
}
