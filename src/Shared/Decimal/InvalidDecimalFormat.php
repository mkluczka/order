<?php

declare(strict_types=1);

namespace Iteo\Shared\Decimal;

final class InvalidDecimalFormat extends \LogicException
{
    public function __construct(float $number)
    {
        parent::__construct("Invalid decimal format, max 2 decimal places are required, $number given");
    }
}
