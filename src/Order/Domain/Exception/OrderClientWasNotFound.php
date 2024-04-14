<?php

declare(strict_types=1);

namespace Iteo\Order\Domain\Exception;

use Iteo\Shared\ClientId;

final class OrderClientWasNotFound extends \DomainException
{
    public function __construct(ClientId $clientId)
    {
        parent::__construct("Order client ($clientId) was not found");
    }
}
