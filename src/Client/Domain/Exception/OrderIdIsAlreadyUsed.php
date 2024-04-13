<?php

declare(strict_types=1);

namespace Iteo\Client\Domain\Exception;

use Iteo\Client\Domain\ValueObject\Order\OrderId;

final class OrderIdIsAlreadyUsed extends \DomainException
{
    public function __construct(OrderId $clientId)
    {
        parent::__construct("Order id ($clientId) is already used");
    }
}
