<?php

declare(strict_types=1);

namespace Iteo\Customer\Domain\Exception;

use Iteo\Customer\Domain\ValueObject\Order\OrderId;

final class OrderIdIsAlreadyUsed extends \DomainException
{
    public function __construct(OrderId $orderId)
    {
        parent::__construct("Order id ($orderId) is already used");
    }
}
