<?php

declare(strict_types=1);

namespace Tests\Functional\Http;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\Utils\EntityAssertions;

final class PlaceOrderControllerTest extends WebTestCase
{
    use EntityAssertions;

    private KernelBrowser $http;

    protected function setUp(): void
    {
        parent::setUp();

        $this->http = self::createClient();
    }

    public function testPlaceOrder(): void
    {
        $orderId = '3102dbc6-617b-41e5-ac2e-0b70e114b45c';
        $clientId = '6fbf322e-cdea-4bc9-9cd5-fceeb2cdec8c';
        $initialBalance = 12.34;

        $this->http->jsonRequest('POST', '/clients', ['clientId' => $clientId, 'balance' => $initialBalance]);

        $products = [
            [
                'productId' => 'PR1',
                'price' => 1.1,
                'weight' => 2.2,
                'quantity' => 5,
            ]
        ];

        $this->http->jsonRequest(
            'POST',
            '/orders',
            [
                'orderId' => $orderId,
                'clientId' => $clientId,
                'products' => $products,
            ]
        );

        self::assertResponseStatusCodeSame(204);

        $this->assertOrdersInDatabase($clientId, [$orderId]);
    }
}
