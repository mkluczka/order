<?php

declare(strict_types=1);

namespace Tests\Integration\Customer\Command;

use Iteo\Customer\Application\Command\CreateCustomer\CreateCustomer;
use Iteo\Customer\Application\Command\PlaceOrder\PlaceOrder;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Tests\IntegrationTestCase;
use Tests\Utils\EntityAssertions;

final class PlaceOrderHandlerTest extends IntegrationTestCase
{
    use EntityAssertions;

    public function testPlaceOrder(): void
    {
        $orderId = '27ffc489-b791-4b18-9ea2-609e3bd96746';
        $customerId = '0b1e3cd3-e806-40b5-8a7e-05b2baa64977';

        $initialBalance = 100.0;
        $expectedBalance = 40.0;

        $this->dispatchCommand(new CreateCustomer($customerId, $initialBalance));

        $this->dispatchCommand(
            new PlaceOrder(
                $orderId,
                $customerId,
                [
                    [
                        'productId' => 'A1',
                        'quantity' => 6,
                        'price' => 10.0,
                        'weight' => 100.0,
                    ]
                ]
            )
        );

        $this->assertCustomerInDatabase($customerId, $expectedBalance);
        $this->assertOrdersInDatabase($customerId, [$orderId]);
    }

    public function testPlaceOrderFailsOnMissingCustomer(): void
    {
        $this->expectException(HandlerFailedException::class);
        $this->expectExceptionMessageMatches('/Customer with id \(def\) was not found/');

        $this->dispatchCommand(
            new PlaceOrder(
                'abc',
                'def',
                [
                    [
                        'productId' => 'A1',
                        'quantity' => 111,
                        'price' => 1.0,
                        'weight' => 1.0,
                    ]
                ],
            )
        );
    }

    public function testPlaceMultipleOrders(): void
    {
        $order1Id = '27ffc489-b791-4b18-9ea2-609e3bd96746';
        $order2Id = '9d3f788c-aa0e-4fd6-87b5-1a28a7fbfa34';
        $order3Id = '45276de9-8f87-4ee7-a9a8-7b9f3ed1321e';
        $customerId = '0b1e3cd3-e806-40b5-8a7e-05b2baa64977';

        $orderItems = [
            [
                'productId' => 'A1',
                'quantity' => 5,
                'price' => 5.0,
                'weight' => 100.0,
            ]
        ];

        $initialBalance = 100.0;
        $expectedBalance = 25.0;

        $this->dispatchCommand(new CreateCustomer($customerId, $initialBalance));

        $this->dispatchCommand(new PlaceOrder($order1Id, $customerId, $orderItems));
        $this->dispatchCommand(new PlaceOrder($order2Id, $customerId, $orderItems));
        $this->dispatchCommand(new PlaceOrder($order3Id, $customerId, $orderItems));

        $this->assertCustomerInDatabase($customerId, $expectedBalance);
        $this->assertOrdersInDatabase($customerId, [$order1Id, $order2Id, $order3Id]);
    }

    public function testCannotPlaceSecondOrderOnSameId(): void
    {
        $orderId = '27ffc489-b791-4b18-9ea2-609e3bd96746';
        $customerId = '0b1e3cd3-e806-40b5-8a7e-05b2baa64977';

        $this->dispatchCommand(new CreateCustomer($customerId, 9990.0));

        $placeOrderCommand = new PlaceOrder(
            $orderId,
            $customerId,
            [
                [
                    'productId' => 'A1',
                    'quantity' => 6,
                    'price' => 10.0,
                    'weight' => 100.0,
                ]
            ]
        );

        $this->dispatchCommand($placeOrderCommand);

        $this->expectException(HandlerFailedException::class);

        $this->dispatchCommand($placeOrderCommand);
    }
}
