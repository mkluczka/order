<?php

declare(strict_types=1);

namespace Iteo\Shared\Money;

use Iteo\Shared\Decimal\Decimal;
use Iteo\Shared\Money\Exception\MoneyAmountMustNotBeNegative;
use JsonSerializable;

final readonly class Money implements \Stringable, JsonSerializable
{
    public function __construct(public Decimal $amount)
    {
        if ($amount->isLessThan(Decimal::fromFloat(0))) {
            throw new MoneyAmountMustNotBeNegative($this->amount);
        }
    }

    public static function fromFloat(float $amount): self
    {
        return new self(Decimal::fromFloat($amount));
    }

    public function plus(self $other): self
    {
        return new self($this->amount->plus($other->amount));
    }

    public function multiplyBy(int $value): self
    {
        return new self($this->amount->multiplyBy($value));
    }

    public function isGreaterThan(self $other): bool
    {
        return $this->amount->isGreaterThan($other->amount);
    }

    public function minus(self $other): self
    {
        return new self($this->amount->minus($other->amount));
    }

    public function equals(self $other): bool
    {
        return $this->amount->equals($other->amount);
    }

    public function asFloat(): float
    {
        return $this->amount->asFloat();
    }

    public function jsonSerialize(): float
    {
        return $this->asFloat();
    }

    public function __toString(): string
    {
        return (string) $this->amount;
    }
}
