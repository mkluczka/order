<?php

declare(strict_types=1);

namespace Iteo\Customer\Domain\CustomerState;

use Iteo\Customer\Domain\Customer;

trait CustomerStateTrait
{
    public function save(): CustomerState
    {
        return new CustomerState(
            $this->id,
            $this->balance,
        );
    }

    public static function restore(CustomerState $state): Customer
    {
        $customer = new Customer($state->customerId);
        $customer->balance = $state->balance;

        return $customer;
    }
}
