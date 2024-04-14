<?php

declare(strict_types=1);

namespace App\Infrastructure\Client;

use App\Infrastructure\Framework\ORM\Repository\ClientEntityRepository;
use Iteo\Client\Ports\Summary\GetSummary;
use Iteo\Client\Ports\Summary\Summary;
use Iteo\Shared\ClientId;
use Iteo\Shared\Money\Money;

final readonly class ORMGetSummary implements GetSummary
{
    public function __construct(private ClientEntityRepository $repository)
    {
    }

    public function byClientId(ClientId $clientId): ?Summary
    {
        $entity = $this->repository->find((string) $clientId);

        return $entity
            ? new Summary($entity->id, Money::fromFloat($entity->balance), $entity->isBlocked)
            : null;
    }
}
