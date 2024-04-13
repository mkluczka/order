<?php

declare(strict_types=1);

namespace Iteo\Client\Application\Command\TopUpClient;

final readonly class TopUpClient
{
    public function __construct(
        public string $clientId,
        public float $additionalAmount,
    ) {
    }
}
