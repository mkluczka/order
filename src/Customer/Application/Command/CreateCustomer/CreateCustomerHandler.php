<?php

declare(strict_types=1);

namespace Iteo\Customer\Application\Command\CreateCustomer;

use Iteo\Customer\Domain\Customer;
use Iteo\Customer\Domain\CustomerRepository;
use Iteo\Customer\Domain\ValueObject\CustomerId;
use Iteo\Shared\Money\Money;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class CreateCustomerHandler
{
    public function __construct(private CustomerRepository $customerRepository)
    {
    }

    public function __invoke(CreateCustomer $command): void
    {
        $customerId = new CustomerId($command->customerId);
        $initialBalance = new Money($command->initialBalance);

        $customer = Customer::create($customerId, $initialBalance);

        $this->customerRepository->save($customer);
    }
}
