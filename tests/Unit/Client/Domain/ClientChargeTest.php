<?php

declare(strict_types=1);

namespace Tests\Unit\Client\Domain;

use Iteo\Client\Domain\Client;
use Iteo\Client\Domain\ClientState\ClientState;
use Iteo\Client\Domain\Event\ClientCharged;
use Iteo\Client\Domain\Exception\InsufficentFunds;
use Iteo\Shared\ClientId;
use Iteo\Shared\Money\Money;
use PHPUnit\Framework\TestCase;

final class ClientChargeTest extends TestCase
{
    public function testCharge(): void
    {
        $clientId = new ClientId('52fa2910-65b8-48df-b585-9e2671ebe288');

        $initialAmount = Money::fromFloat(122.34);
        $additionalAmount = 34.56;

        $resultAmount = Money::fromFloat(87.78);

        $expectedEvents = [new ClientCharged($clientId, $initialAmount, $resultAmount)];

        $client = Client::restore(
            new ClientState(
                $clientId,
                $initialAmount,
                false
            )
        );

        $client->charge(Money::fromFloat($additionalAmount));

        $this->assertEquals($expectedEvents, $client->collectEvents());
        $this->assertEquals($resultAmount, $client->save()->balance);
    }

    public function testChargeWithNothing(): void
    {
        $clientId = new ClientId('52fa2910-65b8-48df-b585-9e2671ebe288');

        $initialAmount = Money::fromFloat(122.34);

        $client = Client::restore(
            new ClientState(
                $clientId,
                $initialAmount,
                false
            )
        );

        $client->charge(Money::fromFloat(0));

        $this->assertEquals([], $client->collectEvents());
        $this->assertEquals($initialAmount, $client->save()->balance);
    }

    public function testChargeWithInsufficientFunds(): void
    {
        $clientId = new ClientId('52fa2910-65b8-48df-b585-9e2671ebe288');

        $initialAmount = Money::fromFloat(122.34);

        $this->expectException(InsufficentFunds::class);

        $client = Client::restore(
            new ClientState(
                $clientId,
                $initialAmount,
                false
            )
        );

        $client->charge(Money::fromFloat(5555));

        $this->assertEquals([], $client->collectEvents());
        $this->assertEquals($initialAmount, $client->save()->balance);
    }
}
