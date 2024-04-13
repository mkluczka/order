<?php

declare(strict_types=1);

namespace App\Client;

use App\Repository\OrderEntityRepository;
use Iteo\Client\Domain\Specification\OrderIdWasUsed;
use Iteo\Client\Domain\ValueObject\Order\OrderId;

final readonly class ORMOrderIdWasUsed implements OrderIdWasUsed
{
    public function __construct(private OrderEntityRepository $repository)
    {
    }

    public function isSatisfiedBy(OrderId $orderId): bool
    {
        $result = $this->repository->find((string) $orderId);

        return empty($result);
    }
}
