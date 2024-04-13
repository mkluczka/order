<?php

declare(strict_types=1);

namespace Iteo\Client\Domain\Exception;

use Iteo\Client\Domain\ValueObject\ClientId;

final class ClientNotFound extends \DomainException
{
    public function __construct(ClientId $clientId)
    {
        parent::__construct("Client with id ($clientId) was not found");
    }
}
