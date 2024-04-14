<?php

declare(strict_types=1);

namespace Iteo\Client\Application\Charge;

use Iteo\Client\Domain\Persistence\ClientRepository;
use Iteo\Shared\ClientId;
use Iteo\Shared\Money\Money;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class ChargeHandler
{
    public function __construct(private ClientRepository $clientRepository)
    {
    }

    public function __invoke(Charge $command): void
    {
        $clientId = new ClientId($command->clientId);

        $client = $this->clientRepository->getByClientId($clientId);
        $client->charge(Money::fromFloat($command->chargeAmount));

        $this->clientRepository->save($client);
    }
}
