<?php

declare(strict_types=1);

namespace Iteo\Client\Domain\Exception;

use Iteo\Shared\ClientId;

final class ClientIdIsAlreadyUsed extends \DomainException
{
    public function __construct(ClientId $orderId)
    {
        parent::__construct("Client id ($orderId) is already used");
    }
}
