<?php

declare(strict_types=1);

namespace Iteo\Shared\DomainEvent;

trait DomainEventsTrait
{
    /** @var DomainEvent[] */
    private array $domainEvents = [];

    private function recordEvent(DomainEvent $event): void
    {
        $this->domainEvents[] = $event;
    }

    /**
     * @return DomainEvent[]
     */
    public function collectEvents(): array
    {
        $collectedEvents = $this->domainEvents;
        $this->domainEvents = [];

        return $collectedEvents;
    }
}
