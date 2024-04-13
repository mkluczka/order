<?php

declare(strict_types=1);

namespace App\Infrastructure\Client;

use App\Infrastructure\Framework\ORM\Repository\ClientEntityRepository;
use Iteo\Client\Domain\Specification\ClientIdWasUsed;
use Iteo\Client\Domain\ValueObject\ClientId;

final readonly class ORMClientIdWasUsed implements ClientIdWasUsed
{
    public function __construct(private ClientEntityRepository $repository)
    {
    }

    public function isSatisfiedBy(ClientId $clientId): bool
    {
        $result = $this->repository->findById($clientId);

        return empty($result);
    }
}
