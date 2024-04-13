<?php

declare(strict_types=1);

namespace Iteo\Customer\Domain\Exception;

use Iteo\Shared\Money\Money;

final class InsufficentFunds extends \DomainException
{
    public function __construct(Money $orderPrice, Money $customerBalance)
    {
        parent::__construct("Order price ($orderPrice) is greater than customer balance ($customerBalance)");
    }
}
