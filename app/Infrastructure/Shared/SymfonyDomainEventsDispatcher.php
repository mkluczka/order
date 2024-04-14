<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared;

use Iteo\Shared\DomainEvent\DomainEvent;
use Iteo\Shared\DomainEvent\DomainEventsDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final readonly class SymfonyDomainEventsDispatcher implements DomainEventsDispatcher
{
    public function __construct(private EventDispatcherInterface $eventDispatcher)
    {
    }

    public function dispatch(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            $this->eventDispatcher->dispatch($event);
        }
    }
}
