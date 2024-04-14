<?php

declare(strict_types=1);

namespace Iteo\Client\Application\Block;

final readonly class BlockClient
{
    public function __construct(public string $clientId)
    {
    }
}
