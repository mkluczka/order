<?php

declare(strict_types=1);

namespace Iteo\Client\Application\TopUpClient;

use Iteo\Client\Domain\Persistence\ClientRepository;
use Iteo\Client\Domain\ValueObject\ClientId;
use Iteo\Shared\Money\Money;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class TopUpClientHandler
{
    public function __construct(private ClientRepository $clientRepository)
    {
    }

    public function __invoke(TopUpClient $command): void
    {
        $clientId = new ClientId($command->clientId);

        $client = $this->clientRepository->getByClientId($clientId);
        $client->topUp(Money::fromFloat($command->additionalAmount));

        $this->clientRepository->save($client);
    }
}
