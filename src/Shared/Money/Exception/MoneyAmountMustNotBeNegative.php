<?php

declare(strict_types=1);

namespace Iteo\Shared\Money\Exception;

use Iteo\Shared\Decimal\Decimal;

final class MoneyAmountMustNotBeNegative extends \DomainException
{
    public function __construct(Decimal $amount)
    {
        parent::__construct("Money amount must not be negative, $amount given");
    }
}
