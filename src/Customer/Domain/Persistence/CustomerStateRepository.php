<?php

declare(strict_types=1);

namespace Iteo\Customer\Domain\Persistence;

use Iteo\Customer\Domain\CustomerState\CustomerState;
use Iteo\Customer\Domain\ValueObject\CustomerId;

interface CustomerStateRepository
{
    public function save(CustomerState $customerState): void;

    public function findByCustomerId(CustomerId $customerId): ?CustomerState;
}
