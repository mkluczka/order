<?php

declare(strict_types=1);

namespace Tests\Unit\Order\Domain;

use Iteo\Order\Domain\Event\OrderCreated;
use Iteo\Order\Domain\Exception\CannotPlaceOrderOnBlockedClient;
use Iteo\Order\Domain\Exception\ClientHasInsufficentFunds;
use Iteo\Order\Domain\Exception\OrderSizeTooSmall;
use Iteo\Order\Domain\Exception\OrderWeightTooBig;
use Iteo\Order\Domain\Order;
use Iteo\Order\Domain\OrderState\OrderState;
use Iteo\Order\Domain\ValueObject\Client;
use Iteo\Order\Domain\ValueObject\Product\Product;
use Iteo\Order\Domain\ValueObject\Product\Quantity;
use Iteo\Order\Domain\ValueObject\Product\Weight;
use Iteo\Order\Domain\ValueObject\ProductList;
use Iteo\Shared\ClientId;
use Iteo\Shared\Money\Money;
use Iteo\Shared\OrderId;
use PHPUnit\Framework\TestCase;

final class OrderTest extends TestCase
{
    public function testCreateOrder(): void
    {
        $orderId = new OrderId('d906401b-58c0-406c-8357-f216c1310f1f');

        $productList = new ProductList([
            $product1 = new Product(
                'PR1',
                Money::fromFloat(200),
                Weight::fromFloat(4800),
                new Quantity(5),
            )
        ]);

        $client = new Client(
            $clientId = new ClientId('9019deed-313a-454f-9714-c1650467b20d'),
            Money::fromFloat(1000),
            false,
        );

        $expectedEvents = [
            new OrderCreated(
                $orderId,
                $clientId,
                Money::fromFloat(1000),
                [$product1]
            )
        ];

        $order = Order::create($orderId, $productList, $client);

        $this->assertEquals($expectedEvents, $order->collectEvents());
        $this->assertEquals(
            new OrderState($orderId, $clientId),
            $order->save(),
        );
    }

    public function testCannotCreateOrderOnBlockedClient(): void
    {
        $orderId = new OrderId('d906401b-58c0-406c-8357-f216c1310f1f');

        $productList = new ProductList([
            new Product(
                'PR1',
                Money::fromFloat(10),
                Weight::fromFloat(10),
                new Quantity(5),
            )
        ]);

        $this->expectException(CannotPlaceOrderOnBlockedClient::class);

        $client = new Client(
            new ClientId('9019deed-313a-454f-9714-c1650467b20d'),
            Money::fromFloat(555),
            true,
        );

        $order = Order::create($orderId, $productList, $client);

        $this->assertEquals([], $order->collectEvents());
    }

    public function testCannotCreateOrderWithInsufficientFunds(): void
    {
        $orderId = new OrderId('d906401b-58c0-406c-8357-f216c1310f1f');

        $productList = new ProductList([
            new Product(
                'PR1',
                Money::fromFloat(10),
                Weight::fromFloat(10),
                new Quantity(5),
            )
        ]);

        $this->expectException(ClientHasInsufficentFunds::class);

        $client = new Client(
            new ClientId('9019deed-313a-454f-9714-c1650467b20d'),
            Money::fromFloat(0),
            false,
        );

        $order = Order::create($orderId, $productList, $client);

        $this->assertEquals([], $order->collectEvents());
    }

    public function testCannotCreateOrderWithTooBigWeight(): void
    {
        $orderId = new OrderId('d906401b-58c0-406c-8357-f216c1310f1f');

        $productList = new ProductList([
            new Product(
                'PR1',
                Money::fromFloat(10),
                Weight::fromFloat(24_001),
                new Quantity(5),
            )
        ]);

        $this->expectException(OrderWeightTooBig::class);

        $client = new Client(
            new ClientId('9019deed-313a-454f-9714-c1650467b20d'),
            Money::fromFloat(1e5),
            false,
        );

        $order = Order::create($orderId, $productList, $client);

        $this->assertEquals([], $order->collectEvents());
    }

    public function testCannoCreateTooSmallOrder(): void
    {
        $orderId = new OrderId('d906401b-58c0-406c-8357-f216c1310f1f');

        $productList = new ProductList([
            new Product(
                'PR1',
                Money::fromFloat(10),
                Weight::fromFloat(1),
                new Quantity(4),
            )
        ]);

        $this->expectException(OrderSizeTooSmall::class);

        $client = new Client(
            new ClientId('9019deed-313a-454f-9714-c1650467b20d'),
            Money::fromFloat(1e5),
            false,
        );

        $order = Order::create($orderId, $productList, $client);

        $this->assertEquals([], $order->collectEvents());
    }
}
