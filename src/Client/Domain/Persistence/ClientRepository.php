<?php

declare(strict_types=1);

namespace Iteo\Client\Domain\Persistence;

use Iteo\Client\Domain\Client;
use Iteo\Client\Domain\Exception\ClientNotFound;
use Iteo\Shared\ClientId;
use Iteo\Shared\DomainEvent\DomainEventsDispatcher;

final readonly class ClientRepository
{
    public function __construct(
        private ClientStateRepository $clientStateRepository,
        private DomainEventsDispatcher $domainEventsDispatcher,
    ) {
    }

    public function save(Client $client): void
    {
        $this->clientStateRepository->save($client->save());

        $this->domainEventsDispatcher->dispatch(...$client->collectEvents());
    }

    public function getByClientId(ClientId $clientId): Client
    {
        $clientState = $this->clientStateRepository->findByClientId($clientId);

        if (null === $clientState) {
            throw new ClientNotFound($clientId);
        }

        return Client::restore($clientState);
    }
}
