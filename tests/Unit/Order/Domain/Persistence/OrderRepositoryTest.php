<?php

declare(strict_types=1);

namespace Tests\Unit\Order\Domain\Persistence;

use Iteo\Order\Domain\Event\OrderCreated;
use Iteo\Order\Domain\Order;
use Iteo\Order\Domain\OrderState\OrderState;
use Iteo\Order\Domain\Persistence\OrderRepository;
use Iteo\Order\Domain\Persistence\OrderStateRepository;
use Iteo\Order\Domain\ValueObject\Product\Product;
use Iteo\Order\Domain\ValueObject\Product\Quantity;
use Iteo\Order\Domain\ValueObject\Product\Weight;
use Iteo\Order\Domain\ValueObject\ProductList;
use Iteo\Shared\ClientId;
use Iteo\Shared\DomainEvent\DomainEventsDispatcher;
use Iteo\Shared\Money\Money;
use Iteo\Shared\OrderId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class OrderRepositoryTest extends TestCase
{
    private OrderRepository $sut;
    private OrderStateRepository|MockObject $orderStateRepositoryMock;
    private DomainEventsDispatcher|MockObject $domainEventsDispatcherMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = new OrderRepository(
            $this->orderStateRepositoryMock = $this->createMock(OrderStateRepository::class),
            $this->domainEventsDispatcherMock = $this->createMock(DomainEventsDispatcher::class),
        );
    }

    public function testSaveOrder(): void
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

        $client = new \Iteo\Order\Domain\ValueObject\Client(
            $clientId = new ClientId('9019deed-313a-454f-9714-c1650467b20d'),
            Money::fromFloat(1000),
            false,
        );

        $expectedOrderState = new OrderState($orderId, $clientId);

        $this->orderStateRepositoryMock
            ->expects($this->once())
            ->method('save')
            ->with($expectedOrderState);

        $this->sut->save(Order::create($orderId, $productList, $client));
    }

    public function testDomainEventsAreDispatchedOnSave(): void
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

        $client = new \Iteo\Order\Domain\ValueObject\Client(
            $clientId = new ClientId('9019deed-313a-454f-9714-c1650467b20d'),
            Money::fromFloat(1000),
            false,
        );

        $expectedEvent = new OrderCreated(
            $orderId,
            $clientId,
            Money::fromFloat(1000),
            [$product1]
        );

        $order = Order::create($orderId, $productList, $client);

        $this->domainEventsDispatcherMock
            ->expects($this->once())
            ->method('dispatch')
            ->with($expectedEvent);

        $this->sut->save($order);
    }
}
