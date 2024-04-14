<?php

declare(strict_types=1);

namespace Iteo\Client\Domain\Exception;

use Iteo\Shared\Money\Money;

final class InsufficentFunds extends \DomainException
{
    public function __construct(Money $chargeAmount, Money $clientBalance)
    {
        parent::__construct("Charge amount ($chargeAmount) is greater than client balance ($clientBalance)");
    }
}
