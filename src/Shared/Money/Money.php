<?php

declare(strict_types=1);

namespace Iteo\Shared\Money;

use Iteo\Shared\Money\Exception\MoneyAmountMustNotBeNegative;

final readonly class Money implements \Stringable
{
    public function __construct(public float $amount)
    {
        if ($amount < 0) {
            throw new MoneyAmountMustNotBeNegative($this->amount);
        }
    }

    public function __toString(): string
    {
        return (string) $this->amount;
    }
}
