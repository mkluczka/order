<?php

declare(strict_types=1);

namespace Iteo\Shared\Money\Exception;

final class MoneyAmountMustNotBeNegative extends \DomainException
{
    public function __construct(float $amount)
    {
        parent::__construct("Money amount must not be negative, $amount given");
    }
}
