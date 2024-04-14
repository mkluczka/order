<?php

declare(strict_types=1);

namespace App\Infrastructure\Order;

use App\Infrastructure\Framework\ORM\Repository\OrderEntityRepository;
use Iteo\Order\Domain\Specification\OrderIdWasUsed;
use Iteo\Shared\OrderId;

final readonly class ORMOrderIdWasUsed implements OrderIdWasUsed
{
    public function __construct(private OrderEntityRepository $repository)
    {
    }

    public function isSatisfiedBy(OrderId $orderId): bool
    {
        $result = $this->repository->find((string) $orderId);

        return !empty($result);
    }
}
