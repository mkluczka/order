<?php

declare(strict_types=1);

namespace Iteo\Client\Application\TopUp;

final readonly class TopUp
{
    public function __construct(
        public string $clientId,
        public float $additionalAmount,
    ) {
    }
}
