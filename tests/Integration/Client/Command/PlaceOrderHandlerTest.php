<?php

declare(strict_types=1);

namespace Tests\Integration\Client\Command;

use Iteo\Client\Application\Command\BlockClient\BlockClient;
use Iteo\Client\Application\Command\CreateClient\CreateClient;
use Iteo\Client\Application\Command\PlaceOrder\PlaceOrder;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Tests\AppTestCase;
use Tests\Utils\EntityAssertions;

final class PlaceOrderHandlerTest extends AppTestCase
{
    use EntityAssertions;

    public function testPlaceOrder(): void
    {
        $orderId = '27ffc489-b791-4b18-9ea2-609e3bd96746';
        $clientId = '0b1e3cd3-e806-40b5-8a7e-05b2baa64977';

        $initialBalance = 100.0;
        $expectedBalance = 40.0;

        $this->dispatchMessage(new CreateClient($clientId, $initialBalance));

        $this->dispatchMessage(
            new PlaceOrder(
                $orderId,
                $clientId,
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

        $this->assertClientInDatabase($clientId, $expectedBalance);
        $this->assertOrdersInDatabase($clientId, [$orderId]);
    }

    public function testPlaceOrderFailsOnMissingClient(): void
    {
        $this->expectException(HandlerFailedException::class);
        $this->expectExceptionMessageMatches('/Client with id \(def\) was not found/');

        $this->dispatchMessage(
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
        $clientId = '0b1e3cd3-e806-40b5-8a7e-05b2baa64977';

        $products = [
            [
                'productId' => 'A1',
                'quantity' => 5,
                'price' => 5.0,
                'weight' => 100.0,
            ]
        ];

        $initialBalance = 100.0;
        $expectedBalance = 25.0;

        $this->dispatchMessage(new CreateClient($clientId, $initialBalance));

        $this->dispatchMessage(new PlaceOrder($order1Id, $clientId, $products));
        $this->dispatchMessage(new PlaceOrder($order2Id, $clientId, $products));
        $this->dispatchMessage(new PlaceOrder($order3Id, $clientId, $products));

        $this->assertClientInDatabase($clientId, $expectedBalance);
        $this->assertOrdersInDatabase($clientId, [$order1Id, $order2Id, $order3Id]);
    }

    public function testCannotPlaceSecondOrderOnSameId(): void
    {
        $orderId = '27ffc489-b791-4b18-9ea2-609e3bd96746';
        $clientId = '0b1e3cd3-e806-40b5-8a7e-05b2baa64977';

        $this->dispatchMessage(new CreateClient($clientId, 9990.0));

        $placeOrderCommand = new PlaceOrder(
            $orderId,
            $clientId,
            [
                [
                    'productId' => 'A1',
                    'quantity' => 6,
                    'price' => 10.0,
                    'weight' => 100.0,
                ]
            ]
        );

        $this->dispatchMessage($placeOrderCommand);

        $this->expectException(HandlerFailedException::class);

        $this->dispatchMessage($placeOrderCommand);
    }

    public function testCannotPlaceOrderOnBlockedClient(): void
    {
        $orderId = '27ffc489-b791-4b18-9ea2-609e3bd96746';
        $clientId = '0b1e3cd3-e806-40b5-8a7e-05b2baa64977';

        $this->dispatchMessage(new CreateClient($clientId, 111.11));
        $this->dispatchMessage(new BlockClient($clientId));

        $this->expectException(HandlerFailedException::class);
        $this->expectExceptionMessageMatches("/Cannot place order on blocked client \($clientId\)/");

        $this->dispatchMessage(
            new PlaceOrder(
                $orderId,
                $clientId,
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

        $this->assertOrdersInDatabase($clientId, []);
    }
}
