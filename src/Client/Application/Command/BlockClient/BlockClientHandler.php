<?php

declare(strict_types=1);

namespace Iteo\Client\Application\Command\BlockClient;

use Iteo\Client\Domain\Persistence\ClientRepository;
use Iteo\Client\Domain\ValueObject\ClientId;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class BlockClientHandler
{
    public function __construct(private ClientRepository $clientRepository)
    {
    }

    public function __invoke(BlockClient $command): void
    {
        $clientId = new ClientId($command->clientId);

        $client = $this->clientRepository->getByClientId($clientId);
        $client->block();

        $this->clientRepository->save($client);
    }
}
