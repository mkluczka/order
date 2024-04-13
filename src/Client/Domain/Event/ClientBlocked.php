<?php

declare(strict_types=1);

namespace Iteo\Client\Domain\Event;

use Iteo\Client\Domain\ValueObject\ClientId;
use Iteo\Shared\DomainEvent\DomainEvent;

final readonly class ClientBlocked implements DomainEvent
{
    public function __construct(public ClientId $clientId)
    {
    }
}
