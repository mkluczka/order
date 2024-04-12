<?php

declare(strict_types=1);

namespace Tests\Utils;

use Iteo\Shared\DomainEvent\DomainEvent;
use Iteo\Shared\DomainEvent\DomainEventsTrait;

final class DomainEventsAwareFake
{
    use DomainEventsTrait;

    public function doSomething(DomainEvent $event): void
    {
        $this->recordEvent($event);
    }
}
