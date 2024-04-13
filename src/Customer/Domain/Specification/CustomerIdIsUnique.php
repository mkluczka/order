<?php

declare(strict_types=1);

namespace Iteo\Customer\Domain\Specification;

use Iteo\Customer\Domain\ValueObject\CustomerId;

interface CustomerIdIsUnique
{
    public function isSatisfiedBy(CustomerId $customerId): bool;
}
