<?php

declare(strict_types=1);

namespace Iteo\Client\Domain\Exception;

use Iteo\Client\Domain\ValueObject\ClientId;

final class CannotPlaceOrderOnBlockedClient extends \DomainException
{
    public function __construct(ClientId $clientId)
    {
        parent::__construct("Cannot place order on blocked client ($clientId)");
    }
}
