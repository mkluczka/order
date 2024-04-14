<?php

declare(strict_types=1);

namespace Iteo\Client\Application\Charge;

final readonly class Charge
{
    public function __construct(public string $clientId, public float $chargeAmount)
    {
    }
}
