<?php

declare(strict_types=1);

namespace Iteo\Client\Application\Block;

final readonly class Block
{
    public function __construct(public string $clientId)
    {
    }
}
