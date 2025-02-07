<?php

declare(strict_types=1);

namespace Iteo\Client\Domain\Persistence;

use Iteo\Client\Domain\ClientState\ClientState;
use Iteo\Shared\ClientId;

interface ClientStateRepository
{
    public function save(ClientState $clientState): void;

    public function findByClientId(ClientId $clientId): ?ClientState;
}
