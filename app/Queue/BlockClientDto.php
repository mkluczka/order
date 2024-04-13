<?php

declare(strict_types=1);

namespace App\Queue;

final readonly class BlockClientDto
{
    public function __construct(public string $clientId)
    {
    }
}
