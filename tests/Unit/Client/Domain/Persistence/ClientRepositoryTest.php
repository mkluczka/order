<?php

declare(strict_types=1);

namespace Tests\Unit\Client\Domain\Persistence;

use Iteo\Client\Domain\Client;
use Iteo\Client\Domain\ClientState\ClientState;
use Iteo\Client\Domain\Event\ClientCreated;
use Iteo\Client\Domain\Exception\ClientNotFound;
use Iteo\Client\Domain\Persistence\ClientRepository;
use Iteo\Client\Domain\Persistence\ClientStateRepository;
use Iteo\Shared\ClientId;
use Iteo\Shared\Decimal\Decimal;
use Iteo\Shared\DomainEvent\DomainEventsDispatcher;
use Iteo\Shared\Money\Money;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class ClientRepositoryTest extends TestCase
{
    private ClientRepository $sut;
    private ClientStateRepository|MockObject $clientStateRepositoryMock;
    private DomainEventsDispatcher|MockObject $domainEventsDispatcherMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = new ClientRepository(
            $this->clientStateRepositoryMock = $this->createMock(ClientStateRepository::class),
            $this->domainEventsDispatcherMock = $this->createMock(DomainEventsDispatcher::class),
        );
    }

    public function testSaveClient(): void
    {
        $clientState = new ClientState(
            new ClientId('5adb7472-1a30-425b-b755-892805ba2065'),
            new Money(Decimal::fromFloat(11.11)),
            false,
        );
        $client = Client::restore($clientState);

        $this->clientStateRepositoryMock
            ->expects($this->once())
            ->method('save')
            ->with($clientState);

        $this->sut->save($client);
    }

    public function testDomainEventsAreDispatchedOnSave(): void
    {
        $clientId = new ClientId('5adb7472-1a30-425b-b755-892805ba2065');
        $initialBalance = new Money(Decimal::fromFloat(11.11));

        $client = Client::create($clientId, $initialBalance);

        $this->domainEventsDispatcherMock
            ->expects($this->once())
            ->method('dispatch')
            ->with(new ClientCreated($clientId, $initialBalance));

        $this->sut->save($client);
    }

    public function testGetByClientId(): void
    {
        $initialClientState = new ClientState(
            $clientId = new ClientId('41bb7289-4a0d-4e08-a874-8d898d0be92b'),
            Money::fromFloat(1),
            false
        );

        $this->clientStateRepositoryMock
            ->expects($this->once())
            ->method('findByClientId')
            ->with($clientId)
            ->willReturn($initialClientState);

        $client = $this->sut->getByClientId($clientId);

        $this->assertEquals($initialClientState, $client->save());
    }

    public function testGetByClientIdFailsWithoutClient(): void
    {
        $this->expectException(ClientNotFound::class);

        $this->sut->getByClientId(new ClientId('8cfbf853-f88e-4626-bd4f-dfb44c7ca9a7'));
    }
}
