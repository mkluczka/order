<?php

declare(strict_types=1);

namespace Tests\Integration\Customer\Command;

use Iteo\Customer\Application\Command\CreateCustomer\CreateCustomer;
use Iteo\Customer\Application\Command\PlaceOrder\PlaceOrder;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Tests\IntegrationTestCase;
use Tests\Utils\CustomerEntityAssertions;

final class PlaceOrderHandlerTest extends IntegrationTestCase
{
    use CustomerEntityAssertions;

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
    }

    public function testPlaceOrderFailsOnMissingCustomer(): void
    {
        $this->expectException(HandlerFailedException::class);
        $this->expectExceptionMessageMatches('/Customer with id \(def\) was not found/');

        $this->dispatchCommand(
            new PlaceOrder(
                'abc',
                'def',
                [],
            )
        );
    }

    // todo testCannotPlaceSecondOrderOnSameId

    //    public function testCannotPlaceSecondOrderOnSameId(): void
    //    {
    //        $orderId = '27ffc489-b791-4b18-9ea2-609e3bd96746';
    //        $customerId = '0b1e3cd3-e806-40b5-8a7e-05b2baa64977';
    //
    //        $this->dispatchCommand(new CreateCustomer($customerId, 9990.0));
    //
    //        $placeOrderCommand = new PlaceOrder(
    //            $orderId,
    //            $customerId,
    //            [
    //                [
    //                    'productId' => 'A1',
    //                    'quantity' => 6,
    //                    'price' => 10.0,
    //                    'weight' => 100.0,
    //                ]
    //            ]
    //        );
    //
    //        $this->dispatchCommand($placeOrderCommand);
    //
    //        $this->expectException(HandlerFailedException::class);
    //
    //        $this->dispatchCommand($placeOrderCommand);
    //    }
}
