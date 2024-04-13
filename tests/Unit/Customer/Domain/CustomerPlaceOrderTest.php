<?php

declare(strict_types=1);

namespace Tests\Unit\Customer\Domain;

use Iteo\Customer\Domain\Customer;
use Iteo\Customer\Domain\CustomerState\CustomerState;
use Iteo\Customer\Domain\Event\CustomerCharged;
use Iteo\Customer\Domain\Event\OrderPlaced;
use Iteo\Customer\Domain\Exception\InsufficentFunds;
use Iteo\Customer\Domain\ValueObject\CustomerId;
use Iteo\Customer\Domain\ValueObject\Order\Order;
use Iteo\Customer\Domain\ValueObject\Order\OrderId;
use Iteo\Shared\Money\Money;
use PHPUnit\Framework\TestCase;
use Tests\Utils\OrderItemGenerator;

final class CustomerPlaceOrderTest extends TestCase
{
    public function testPlaceOrder(): void
    {
        $customerId = new CustomerId('e4f71eac-54c6-4dc9-972f-3429529b15bb');
        $initialBalance = Money::fromFloat(999);

        $orderId = new OrderId('4c56a7a6-329b-42ca-b5d8-ae65fb35770f');
        $orderItems = [
            OrderItemGenerator::orderItem(5, 2, 3),
            OrderItemGenerator::orderItem(5, 3, 4),
        ];
        $order = new Order($orderId, $orderItems);

        $expectedEvents = [
            new OrderPlaced($orderId, $customerId, $orderItems),
            new CustomerCharged($customerId, $initialBalance, Money::fromFloat(964)),
        ];

        $customer = Customer::restore(
            new CustomerState(
                $customerId,
                $initialBalance,
            )
        );

        $customer->placeOrder($order);

        $this->assertEquals($expectedEvents, $customer->collectEvents());
    }

    public function testPlaceOrderFreeOfCharge(): void
    {
        $customerId = new CustomerId('e4f71eac-54c6-4dc9-972f-3429529b15bb');
        $initialBalance = Money::fromFloat(999);

        $orderId = new OrderId('4c56a7a6-329b-42ca-b5d8-ae65fb35770f');
        $orderItems = [OrderItemGenerator::orderItem(5, 2, 0)];
        $order = new Order($orderId, $orderItems);

        $expectedEvents = [new OrderPlaced($orderId, $customerId, $orderItems)];

        $customer = Customer::restore(
            new CustomerState(
                $customerId,
                $initialBalance,
            )
        );

        $customer->placeOrder($order);

        $this->assertEquals($expectedEvents, $customer->collectEvents());
    }

    public function testPlaceOrderOnInsufficientFunds(): void
    {
        $customerId = new CustomerId('e4f71eac-54c6-4dc9-972f-3429529b15bb');
        $initialBalance = Money::fromFloat(0);

        $orderId = new OrderId('4c56a7a6-329b-42ca-b5d8-ae65fb35770f');
        $orderItems = [OrderItemGenerator::orderItem(5, 2, 1)];
        $order = new Order($orderId, $orderItems);

        $this->expectException(InsufficentFunds::class);
        $this->expectExceptionMessageMatches('/Order price \(5\.00\)/');

        $customer = Customer::restore(
            new CustomerState(
                $customerId,
                $initialBalance,
            )
        );

        $customer->placeOrder($order);

        $this->assertEquals([], $customer->collectEvents());
    }
}
