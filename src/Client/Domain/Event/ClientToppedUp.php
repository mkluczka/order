<?php

declare(strict_types=1);

namespace Iteo\Client\Domain\Event;

use Iteo\Client\Domain\ValueObject\ClientId;
use Iteo\Shared\DomainEvent\DomainEvent;
use Iteo\Shared\Money\Money;

final readonly class ClientToppedUp implements DomainEvent
{
    public function __construct(
        public ClientId $clientId,
        public Money $previousBalance,
        public Money $currentBalance,
    ) {
    }
}
