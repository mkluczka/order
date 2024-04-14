<?php

declare(strict_types=1);

namespace Iteo\Client\Ports\Summary;

use Iteo\Shared\ClientId;

interface GetSummary
{
    public function byClientId(ClientId $clientId): ?Summary;
}
