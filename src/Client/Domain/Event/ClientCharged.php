<?php

declare(strict_types=1);

namespace Iteo\Client\Domain\Event;

use Iteo\Shared\ClientId;
use Iteo\Shared\DomainEvent\DomainEvent;
use Iteo\Shared\Money\Money;

final readonly class ClientCharged implements DomainEvent
{
    public function __construct(
        public ClientId $clientId,
        public Money $previousBalance,
        public Money $currentBalance,
    ) {
    }
}
