<?php

declare(strict_types=1);

namespace Iteo\Customer\Application\Command\CreateCustomer;

use Iteo\Customer\Domain\Customer;
use Iteo\Customer\Domain\Exception\CustomerIdIsAlreadyUsed;
use Iteo\Customer\Domain\Persistence\CustomerRepository;
use Iteo\Customer\Domain\Specification\CustomerIdIsUnique;
use Iteo\Customer\Domain\ValueObject\CustomerId;
use Iteo\Shared\Decimal\Decimal;
use Iteo\Shared\Money\Money;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class CreateCustomerHandler
{
    public function __construct(
        private CustomerIdIsUnique $customerIdIsUnique,
        private CustomerRepository $customerRepository,
    ) {
    }

    public function __invoke(CreateCustomer $command): void
    {
        $customerId = new CustomerId($command->customerId);
        $initialBalance = new Money(Decimal::fromFloat($command->initialBalance));

        if (!$this->customerIdIsUnique->isSatisfiedBy($customerId)) {
            throw new CustomerIdIsAlreadyUsed($customerId);
        }

        $customer = Customer::create($customerId, $initialBalance);

        $this->customerRepository->save($customer);
    }
}
