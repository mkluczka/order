<?php

declare(strict_types=1);

namespace Iteo\Client\Application\Command\BlockClient;

final readonly class BlockClient
{
    public function __construct(public string $clientId)
    {
    }
}
