<?php

declare(strict_types=1);

namespace Tests\Functional;

use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\Utils\EntityAssertions;

final class CreateClientControllerTest extends WebTestCase
{
    use EntityAssertions;

    private KernelBrowser $http;

    protected function setUp(): void
    {
        parent::setUp();

        $this->http = self::createClient();
    }

    public function testCreateCustomer(): void
    {
        $clientId = '6fbf322e-cdea-4bc9-9cd5-fceeb2cdec8c';
        $initialBalance = 12.34;

        $this->http->jsonRequest(
            'POST',
            '/clients',
            [
                'clientId' => $clientId,
                'balance' => $initialBalance,
            ]
        );

        self::assertResponseStatusCodeSame(204);
        $this->assertClientInDatabase($clientId, $initialBalance);
    }

    /**
     * @param array<mixed> $payload
     * @param array<mixed> $errors
     */
    #[DataProvider('provideFailWithInvalidRequest')]
    public function testFailWithInvalidRequest(array $payload, array $errors): void
    {
        $this->http->jsonRequest(
            'POST',
            '/clients',
            $payload,
        );

        self::assertResponseStatusCodeSame(400);

        $response = json_decode((string) $this->http->getResponse()->getContent(), true);

        $this->assertEquals($errors, $response);

        $this->assertNoClientInDatabase();
    }

    /**
     * @return iterable<mixed>
     */
    public static function provideFailWithInvalidRequest(): iterable
    {
        yield [
            [],
            [
                'clientId' => 'Invalid value',
                'balance' => 'Invalid value',
            ],
        ];

        yield [
            ['clientId' => 15, 'balance' => 'abc'],
            [
                'clientId' => 'Invalid value',
                'balance' => 'Invalid value',
            ]
        ];

        yield [
            ['clientId' => 'abc', 'balance' => 'abc'],
            [
                'clientId' => 'Invalid value',
                'balance' => 'Invalid value',
            ]
        ];

        yield [
            ['clientId' => 'ecdc369f-6221-4a89-b715-b0f8b5b5c850', 'balance' => 'abc'],
            [
                'balance' => 'Invalid value',
            ]
        ];

        yield [
            ['clientId' => 'abc', 'balance' => 12.34],
            [
                'clientId' => 'Invalid value',
            ],
        ];
    }
}
