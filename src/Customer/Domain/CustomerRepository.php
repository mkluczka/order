<?php

declare(strict_types=1);

namespace Iteo\Customer\Domain;

use Iteo\Customer\Domain\CustomerState\CustomerStateRepository;
use Iteo\Customer\Domain\Exception\CustomerNotFound;
use Iteo\Customer\Domain\ValueObject\CustomerId;
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

    public function getByCustomerId(CustomerId $customerId): Customer
    {
        $customerState = $this->customerStateRepository->findByCustomerId($customerId);

        if (null === $customerState) {
            throw new CustomerNotFound($customerId);
        }

        return Customer::restore($customerState);
    }
}
