<?php

declare(strict_types=1);

namespace Tests\Unit\Client\Domain;

use Iteo\Client\Domain\Client;
use Iteo\Client\Domain\ClientState\ClientState;
use Iteo\Client\Domain\Event\ClientToppedUp;
use Iteo\Client\Domain\ValueObject\ClientId;
use Iteo\Shared\Money\Money;
use PHPUnit\Framework\TestCase;

final class ClientTopUpTest extends TestCase
{
    public function testTopUp(): void
    {
        $clientId = new ClientId('52fa2910-65b8-48df-b585-9e2671ebe288');

        $initialAmount = Money::fromFloat(122.34);
        $additionalAmount = 34.56;

        $resultAmount = Money::fromFloat(156.90);

        $expectedEvents = [new ClientToppedUp($clientId, $initialAmount, $resultAmount)];

        $client = Client::restore(
            new ClientState(
                $clientId,
                $initialAmount,
                [],
                false
            )
        );

        $client->topUp(Money::fromFloat($additionalAmount));

        $this->assertEquals($expectedEvents, $client->collectEvents());
        $this->assertEquals($resultAmount, $client->save()->balance);
    }

    public function testTopUpWithNothing(): void
    {
        $clientId = new ClientId('52fa2910-65b8-48df-b585-9e2671ebe288');

        $initialAmount = Money::fromFloat(122.34);

        $client = Client::restore(
            new ClientState(
                $clientId,
                $initialAmount,
                [],
                false
            )
        );

        $client->topUp(Money::fromFloat(0));

        $this->assertEquals([], $client->collectEvents());
        $this->assertEquals($initialAmount, $client->save()->balance);
    }
}
