<?php

declare(strict_types=1);

namespace Iteo\Order\Domain\Exception;

use Iteo\Shared\ClientId;

final class CannotPlaceOrderOnBlockedClient extends \DomainException
{
    public function __construct(ClientId $orderId)
    {
        parent::__construct("Cannot place order on blocked client ($orderId)");
    }
}
