<?php

declare(strict_types=1);

namespace Iteo\Customer\Domain;

use Iteo\Customer\Domain\CustomerState\CustomerStateRepository;

final readonly class CustomerRepository
{
    public function __construct(private CustomerStateRepository $customerStateRepository)
    {
    }

    public function save(Customer $customer): void
    {
        $this->customerStateRepository->save($customer->save());
    }
}
