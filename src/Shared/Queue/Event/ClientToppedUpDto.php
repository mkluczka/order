<?php

declare(strict_types=1);

namespace Iteo\Shared\Queue\Event;

final readonly class ClientToppedUpDto
{
    public function __construct(
        public string $clientId,
        public float $additionalAmount,
    ) {
    }
}
