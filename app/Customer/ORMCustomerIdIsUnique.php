<?php

declare(strict_types=1);

namespace App\Customer;

use App\Repository\CustomerEntityRepository;
use Iteo\Customer\Domain\Specification\CustomerIdIsUnique;
use Iteo\Customer\Domain\ValueObject\CustomerId;

final readonly class ORMCustomerIdIsUnique implements CustomerIdIsUnique
{
    public function __construct(private CustomerEntityRepository $repository)
    {
    }

    public function isSatisfiedBy(CustomerId $customerId): bool
    {
        $result = $this->repository->findOneBy(['uuid' => (string) $customerId]);

        return empty($result);
    }
}
