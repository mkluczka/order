<?php

declare(strict_types=1);

namespace Tests\Integration\Shared;

use App\Shared\SymfonyDomainEventsDispatcher;
use Iteo\Shared\DomainEvent\DomainEventsDispatcher;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Tests\IntegrationTestCase;
use Tests\Utils\FakeDomainEvent;

#[CoversClass(SymfonyDomainEventsDispatcher::class)]
final class DomainEventsDispatcherTest extends IntegrationTestCase
{
    private DomainEventsDispatcher $sut;
    private EventDispatcher $eventDispatcher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = self::getContainer()->get(DomainEventsDispatcher::class);

        $this->eventDispatcher = self::getContainer()->get('event_dispatcher');
    }

    public function testDispatchEvents(): void
    {
        $event1 = new FakeDomainEvent(1);
        $event2 = new FakeDomainEvent(2);

        $expectedEvents = [$event1, $event2];

        $listenedEvents = [];
        $this->eventDispatcher->addListener(
            FakeDomainEvent::class,
            function (FakeDomainEvent $event) use (&$listenedEvents): void {
                $listenedEvents[] = $event;
            },
        );

        $this->sut->dispatch($event1, $event2);

        $this->assertEquals($expectedEvents, $listenedEvents);
    }
}
