<?php

declare(strict_types=1);

namespace Iteo\Client\Domain\Exception;

use Iteo\Shared\ClientId;

final class ClientNotFound extends \DomainException
{
    public function __construct(ClientId $orderId)
    {
        parent::__construct("Client with id ($orderId) was not found");
    }
}
