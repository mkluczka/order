<?php

declare(strict_types=1);

namespace Iteo\Customer\Domain;

use Iteo\Customer\Domain\CustomerState\CustomerStateRepository;
use Iteo\Shared\DomainEvent\DomainEventsDispatcher;

final readonly class CustomerRepository
{
    public function __construct(
        private CustomerStateRepository $customerStateRepository,
        private DomainEventsDispatcher $domainEventsDispatcher,
    ) {
    }

    public function save(Customer $customer): void
    {
        $this->customerStateRepository->save($customer->save());

        $this->domainEventsDispatcher->dispatch(...$customer->collectEvents());
    }
}
