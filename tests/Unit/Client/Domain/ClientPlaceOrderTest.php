<?php

declare(strict_types=1);

namespace Tests\Unit\Client\Domain;

use Iteo\Client\Domain\Client;
use Iteo\Client\Domain\ClientState\ClientState;
use Iteo\Client\Domain\Event\ClientCharged;
use Iteo\Client\Domain\Event\OrderPlaced;
use Iteo\Client\Domain\Exception\InsufficentFunds;
use Iteo\Client\Domain\ValueObject\ClientId;
use Iteo\Client\Domain\ValueObject\Order\Order;
use Iteo\Client\Domain\ValueObject\Order\OrderId;
use Iteo\Shared\Money\Money;
use PHPUnit\Framework\TestCase;
use Tests\Utils\ProductGenerator;

final class ClientPlaceOrderTest extends TestCase
{
    public function testPlaceOrder(): void
    {
        $clientId = new ClientId('e4f71eac-54c6-4dc9-972f-3429529b15bb');
        $initialBalance = Money::fromFloat(999);

        $orderId = new OrderId('4c56a7a6-329b-42ca-b5d8-ae65fb35770f');
        $products = [
            ProductGenerator::product(5, 2, 3),
            ProductGenerator::product(5, 3, 4),
        ];
        $order = new Order($orderId, $products);

        $expectedEvents = [
            new OrderPlaced($orderId, $clientId, $products),
            new ClientCharged($clientId, $initialBalance, Money::fromFloat(964)),
        ];

        $client = Client::restore(
            new ClientState(
                $clientId,
                $initialBalance,
                [],
                false,
            )
        );

        $client->placeOrder($order);

        $this->assertEquals($expectedEvents, $client->collectEvents());
        $this->assertEquals([$orderId], $client->save()->orders);
    }

    public function testPlaceOrderFreeOfCharge(): void
    {
        $clientId = new ClientId('e4f71eac-54c6-4dc9-972f-3429529b15bb');
        $initialBalance = Money::fromFloat(999);

        $orderId = new OrderId('4c56a7a6-329b-42ca-b5d8-ae65fb35770f');
        $products = [ProductGenerator::product(5, 2, 0)];
        $order = new Order($orderId, $products);

        $expectedEvents = [new OrderPlaced($orderId, $clientId, $products)];

        $client = Client::restore(
            new ClientState(
                $clientId,
                $initialBalance,
                [],
                false,
            )
        );

        $client->placeOrder($order);

        $this->assertEquals($expectedEvents, $client->collectEvents());
        $this->assertEquals([$orderId], $client->save()->orders);
    }

    public function testPlaceOrderOnInsufficientFunds(): void
    {
        $clientId = new ClientId('e4f71eac-54c6-4dc9-972f-3429529b15bb');
        $initialBalance = Money::fromFloat(0);

        $orderId = new OrderId('4c56a7a6-329b-42ca-b5d8-ae65fb35770f');
        $products = [ProductGenerator::product(5, 2, 1)];
        $order = new Order($orderId, $products);

        $this->expectException(InsufficentFunds::class);
        $this->expectExceptionMessageMatches('/Order price \(5\.00\)/');

        $client = Client::restore(
            new ClientState(
                $clientId,
                $initialBalance,
                [],
                false,
            )
        );

        $client->placeOrder($order);

        $this->assertEquals([], $client->collectEvents());
        $this->assertEquals([], $client->save()->orders);
    }

    public function testAddingSameOrderSecondTime(): void
    {
        $clientId = new ClientId('e4f71eac-54c6-4dc9-972f-3429529b15bb');
        $initialBalance = Money::fromFloat(999);

        $orderId = new OrderId('4c56a7a6-329b-42ca-b5d8-ae65fb35770f');
        $products = [
            ProductGenerator::product(5, 2, 3),
            ProductGenerator::product(5, 3, 4),
        ];
        $order = new Order($orderId, $products);

        $expectedEvents = [
            new OrderPlaced($orderId, $clientId, $products),
            new ClientCharged($clientId, $initialBalance, Money::fromFloat(964)),
        ];

        $client = Client::restore(
            new ClientState(
                $clientId,
                $initialBalance,
                [],
                false,
            )
        );

        $client->placeOrder($order);
        $client->placeOrder($order);

        $this->assertEquals($expectedEvents, $client->collectEvents());
        $this->assertEquals([$orderId], $client->save()->orders);
    }
}
