<?php

declare(strict_types=1);

namespace App\Customer;

use App\Repository\CustomerEntityRepository;
use Iteo\Customer\Domain\Specification\CustomerIdWasUsed;
use Iteo\Customer\Domain\ValueObject\CustomerId;

final readonly class ORMCustomerIdWasUsed implements CustomerIdWasUsed
{
    public function __construct(private CustomerEntityRepository $repository)
    {
    }

    public function isSatisfiedBy(CustomerId $customerId): bool
    {
        $result = $this->repository->findById($customerId);

        return empty($result);
    }
}
