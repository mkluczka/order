<?php

declare(strict_types=1);

namespace Iteo\Shared\DomainEvent;

interface DomainEventsDispatcher
{
    public function dispatch(DomainEvent ...$events): void;
}
