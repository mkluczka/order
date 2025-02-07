<?php

declare(strict_types=1);

namespace Tests\Unit\Shared\DomainEvent;

use Iteo\Shared\DomainEvent\DomainEvent;
use PHPUnit\Framework\TestCase;
use Tests\Utils\DomainEventsAwareFake;

final class DomainEventsTraitTest extends TestCase
{
    public function testCollectEvents(): void
    {
        $events = array_fill(
            0,
            5,
            new class() implements DomainEvent {
            }
        );

        $sut = new DomainEventsAwareFake();

        foreach ($events as $event) {
            $sut->doSomething($event);
        }

        $this->assertEquals($events, $sut->collectEvents());
        $this->assertEquals([], $sut->collectEvents());
    }
}
