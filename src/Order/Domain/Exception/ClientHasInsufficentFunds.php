<?php

declare(strict_types=1);

namespace Iteo\Order\Domain\Exception;

use Iteo\Shared\Money\Money;

final class ClientHasInsufficentFunds extends \DomainException
{
    public function __construct(Money $chargeAmount, Money $clientBalance)
    {
        parent::__construct("Order price ($chargeAmount) is greater than client balance ($clientBalance)");
    }
}
