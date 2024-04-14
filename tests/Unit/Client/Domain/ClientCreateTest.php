<?php

declare(strict_types=1);

namespace Tests\Unit\Client\Domain;

use Iteo\Client\Domain\Client;
use Iteo\Client\Domain\Event\ClientCreated;
use Iteo\Shared\ClientId;
use Iteo\Shared\Decimal\Decimal;
use Iteo\Shared\Money\Money;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

final class ClientCreateTest extends TestCase
{
    #[TestWith(['b13d1d65-1866-4e04-af33-6ea236d224de', 55.55])]
    #[TestWith(['ad85a94f-3d2d-41d3-8ac9-eede433420ee', 11.11])]
    #[TestWith(['e329e5e3-fcdd-4481-af26-98b79edd08bf', 0.0])]
    #[TestWith(['e329e5e3-fcdd-4481-af26-98b79edd08bf', 0])]
    public function testCreate(string $clientId, float $initialBalance): void
    {
        $clientId = new ClientId($clientId);
        $initialBalance = new Money(Decimal::fromFloat($initialBalance));

        $expectedEvents = [
            new ClientCreated($clientId, $initialBalance),
        ];

        $client = Client::create($clientId, $initialBalance);

        $this->assertEquals($expectedEvents, $client->collectEvents());
    }
}
