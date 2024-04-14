<?php

declare(strict_types=1);

namespace Iteo\Client\Domain\ClientState;

use Iteo\Shared\ClientId;
use Iteo\Shared\Money\Money;

final readonly class ClientState
{
    public function __construct(
        public ClientId $clientId,
        public Money $balance,
        public bool $isBlocked,
    ) {
    }
}
