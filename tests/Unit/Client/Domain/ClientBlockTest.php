<?php

declare(strict_types=1);

namespace Tests\Unit\Client\Domain;

use Iteo\Client\Domain\Client;
use Iteo\Client\Domain\ClientState\ClientState;
use Iteo\Client\Domain\Event\ClientBlocked;
use Iteo\Client\Domain\ValueObject\ClientId;
use Iteo\Shared\Money\Money;
use PHPUnit\Framework\TestCase;

final class ClientBlockTest extends TestCase
{
    public function testBlockClient(): void
    {
        $clientId = new ClientId('ef6343d5-f78d-4fe8-b896-a876abb6a3e0');

        $expectedEvents = [new ClientBlocked($clientId)];

        $sut = Client::restore(
            new ClientState(
                $clientId,
                Money::fromFloat(13.13),
                [],
                false
            )
        );

        $sut->block();

        $this->assertEquals($expectedEvents, $sut->collectEvents());
        $this->assertTrue($sut->save()->isBlocked);
    }

    public function testBlockDoesNothingOnBlockedClient(): void
    {
        $clientId = new ClientId('ef6343d5-f78d-4fe8-b896-a876abb6a3e0');

        $sut = Client::restore(
            new ClientState(
                $clientId,
                Money::fromFloat(13.13),
                [],
                true
            )
        );

        $sut->block();

        $this->assertEquals([], $sut->collectEvents());
    }
}
