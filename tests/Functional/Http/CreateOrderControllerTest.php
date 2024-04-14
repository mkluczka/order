<?php

declare(strict_types=1);

namespace Tests\Functional\Http;

use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\Utils\ClientDB;
use Tests\Utils\EntityAssertions;

final class CreateOrderControllerTest extends WebTestCase
{
    use EntityAssertions;
    use ClientDB;

    private const CLIENT_ID = 'bd40d23f-bf31-4d4c-a1f2-337430f0a16d';

    private KernelBrowser $http;

    protected function setUp(): void
    {
        parent::setUp();

        $this->http = self::createClient();

        self::dbAddClient(self::CLIENT_ID);
    }

    public function testCreateOrder(): void
    {
        $orderId = '3102dbc6-617b-41e5-ac2e-0b70e114b45c';

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
                'clientId' => self::CLIENT_ID,
                'products' => $products,
            ]
        );

        self::assertResponseStatusCodeSame(204);

        $this->assertOrdersInDatabase(self::CLIENT_ID, [$orderId]);
    }

    public function testCreateOrderWithoutProducts(): void
    {
        $orderId = '3102dbc6-617b-41e5-ac2e-0b70e114b45c';

        $this->http->jsonRequest(
            'POST',
            '/orders',
            [
                'orderId' => $orderId,
                'clientId' => self::CLIENT_ID,
                'products' => [],
            ]
        );

        self::assertResponseStatusCodeSame(409);

        $this->assertResponseEquals(['error' => 'Order size too small, 0 given, at least 5 required']);

        $this->assertNoOrdersInDatabase();
    }

    /**
     * @param array<mixed> $payload
     * @param array<mixed> $errors
     * @return void
     */
    #[DataProvider('provideCreateOrderValidation')]
    public function testCreateOrderValidation(array $payload, array $errors): void
    {
        $this->http->jsonRequest('POST', '/orders', $payload);

        self::assertResponseStatusCodeSame(400);

        $this->assertResponseEquals($errors);

        $this->assertNoOrdersInDatabase();
    }

    /**
     * @param array<mixed> $expectedResponse
     * @return void
     */
    private function assertResponseEquals(array $expectedResponse): void
    {
        $response = json_decode((string) $this->http->getResponse()->getContent(), true);

        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * @return iterable<mixed>
     */
    public static function provideCreateOrderValidation(): iterable
    {
        yield [
            [],
            [
                '[orderId]' => 'This field is missing.',
                '[clientId]' => 'This field is missing.',
                '[products]' => 'This field is missing.'
            ]
        ];

        yield [
            [
                'orderId' => 123,
                'clientId' => 123,
                'products' => [],
            ],
            [
                '[orderId]' => 'This is not a valid UUID.',
                '[clientId]' => 'This is not a valid UUID.'
            ]
        ];

        yield [
            [
                'orderId' => 'abc',
                'clientId' => 'abc',
                'products' => [],
            ],
            [
                '[orderId]' => 'This is not a valid UUID.',
                '[clientId]' => 'This is not a valid UUID.'
            ]
        ];

        yield [
            [
                'orderId' => 'a2bbb490-1af3-42b8-b1ea-db50a2437f69',
                'clientId' => '93e3e491-88a8-4ac1-ac0d-3ce8e95b418f',
                'products' => [
                    [

                    ]
                ]
            ],
            [
                '[products][0][productId]' => 'This field is missing.',
                '[products][0][quantity]' => 'This field is missing.',
                '[products][0][price]' => 'This field is missing.',
                '[products][0][weight]' => 'This field is missing.'
            ]
        ];

        yield [
            [
                'orderId' => 'a2bbb490-1af3-42b8-b1ea-db50a2437f69',
                'clientId' => '93e3e491-88a8-4ac1-ac0d-3ce8e95b418f',
                'products' => [
                    [
                        'productId' => 123,
                        'quantity' => 'abc',
                        'price' => 'abc',
                        'weight' => 'abc',
                    ]
                ]
            ],
            [
                '[products][0][productId]' => 'This value should be of type string.',
                '[products][0][quantity]' => 'This value should be of type int.',
                '[products][0][price]' => 'This value should be of type number.',
                '[products][0][weight]' => 'This value should be of type number.'
            ]
        ];

        yield [
            [
                'orderId' => 'a2bbb490-1af3-42b8-b1ea-db50a2437f69',
                'clientId' => '93e3e491-88a8-4ac1-ac0d-3ce8e95b418f',
                'products' => [
                    [
                        'productId' => 123,
                        'quantity' => 12.3,
                        'price' => [],
                        'weight' => [],
                    ]
                ]
            ],
            [
                '[products][0][productId]' => 'This value should be of type string.',
                '[products][0][quantity]' => 'This value should be of type int.',
                '[products][0][price]' => 'This value should be of type number.',
                '[products][0][weight]' => 'This value should be of type number.'
            ]
        ];
    }
}
