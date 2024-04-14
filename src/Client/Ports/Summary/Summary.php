<?php

declare(strict_types=1);

namespace Iteo\Client\Ports\Summary;

use Iteo\Shared\Money\Money;

final readonly class Summary
{
    public function __construct(
        public string $clientId,
        public Money $balance,
        public bool $isBlocked,
    ) {
    }
}
