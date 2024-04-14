<?php

declare(strict_types=1);

namespace Iteo\Order\Domain\Exception;

use Iteo\Shared\OrderId;

final class OrderIdIsAlreadyUsed extends \DomainException
{
    public function __construct(OrderId $orderId)
    {
        parent::__construct("Order id ($orderId) is already used");
    }
}
