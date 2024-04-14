<?php

declare(strict_types=1);

namespace Iteo\Client\Application\Create;

final readonly class Create
{
    public function __construct(
        public string $clientId,
        public float $initialBalance,
    ) {
    }
}
