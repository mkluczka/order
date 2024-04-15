<?php

declare(strict_types=1);

namespace Tests\Functional\Http;

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

    public function testCreateClient(): void
    {
        $clientId = '6fbf322e-cdea-4bc9-9cd5-fceeb2cdec8c';
        $clientName = 'test name';
        $initialBalance = 12.34;

        $this->http->jsonRequest(
            'POST',
            '/clients',
            [
                'clientId' => $clientId,
                'name' => $clientName,
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
                '[clientId]' => 'This field is missing.',
                '[balance]' => 'This field is missing.',
                '[name]' => 'This field is missing.'
            ],
        ];

        yield [
            ['clientId' => 15, 'balance' => 'abc', 'name' => 15],
            [
                '[clientId]' => 'This is not a valid UUID.',
                '[balance]' => 'This value should be of type number.',
                '[name]' => 'This value should be of type string.'
            ]
        ];

        yield [
            ['clientId' => 'abc', 'balance' => 'abc', 'name' => 'abc'],
            [
                '[clientId]' => 'This is not a valid UUID.',
                '[balance]' => 'This value should be of type number.'
            ]
        ];

        yield [
            ['clientId' => 'ecdc369f-6221-4a89-b715-b0f8b5b5c850', 'balance' => 'abc', 'name' => 'abc'],
            [
                '[balance]' => 'This value should be of type number.'
            ]
        ];

        yield [
            ['clientId' => 'abc', 'balance' => 12.34, 'name' => 'abc'],
            [
                '[clientId]' => 'This is not a valid UUID.'
            ],
        ];
    }

    public function testIgnoreClientName(): void
    {
        $clientId = '6fbf322e-cdea-4bc9-9cd5-fceeb2cdec8c';
        $initialBalance = 12.34;

        $this->http->jsonRequest(
            'POST',
            '/clients',
            [
                'clientId' => $clientId,
                'name' => 'should be ignored',
                'balance' => $initialBalance,
            ]
        );

        self::assertResponseStatusCodeSame(204);
        $this->assertClientInDatabase($clientId, $initialBalance);
    }
}
