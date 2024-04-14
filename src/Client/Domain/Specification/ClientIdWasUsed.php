<?php

declare(strict_types=1);

namespace Iteo\Client\Domain\Specification;

use Iteo\Shared\ClientId;

interface ClientIdWasUsed
{
    public function isSatisfiedBy(ClientId $clientId): bool;
}
