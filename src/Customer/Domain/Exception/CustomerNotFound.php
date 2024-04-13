<?php

declare(strict_types=1);

namespace Iteo\Customer\Domain\Exception;

use Iteo\Customer\Domain\ValueObject\CustomerId;

final class CustomerNotFound extends \DomainException
{
    public function __construct(CustomerId $customerId)
    {
        parent::__construct("Customer with id ($customerId) was not found");
    }
}
