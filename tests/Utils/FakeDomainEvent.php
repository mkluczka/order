<?php

declare(strict_types=1);

namespace Tests\Utils;

use Iteo\Shared\DomainEvent\DomainEvent;

final readonly class FakeDomainEvent implements DomainEvent
{
    public function __construct(public int $value)
    {
    }
}
