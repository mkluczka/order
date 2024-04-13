<?php

declare(strict_types=1);

namespace Iteo\Client\Application\Command\CreateClient;

use Iteo\Client\Domain\Client;
use Iteo\Client\Domain\Exception\ClientIdIsAlreadyUsed;
use Iteo\Client\Domain\Persistence\ClientRepository;
use Iteo\Client\Domain\Specification\ClientIdWasUsed;
use Iteo\Client\Domain\ValueObject\ClientId;
use Iteo\Shared\Decimal\Decimal;
use Iteo\Shared\Money\Money;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class CreateClientHandler
{
    public function __construct(
        private ClientIdWasUsed $clientIdWasUsed,
        private ClientRepository $clientRepository,
    ) {
    }

    public function __invoke(CreateClient $command): void
    {
        $clientId = new ClientId($command->clientId);
        $initialBalance = new Money(Decimal::fromFloat($command->initialBalance));

        if (!$this->clientIdWasUsed->isSatisfiedBy($clientId)) {
            throw new ClientIdIsAlreadyUsed($clientId);
        }

        $client = Client::create($clientId, $initialBalance);

        $this->clientRepository->save($client);
    }
}
