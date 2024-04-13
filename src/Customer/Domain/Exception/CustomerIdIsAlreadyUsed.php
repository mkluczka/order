<?php

declare(strict_types=1);

namespace Iteo\Customer\Domain\Exception;

use Iteo\Customer\Domain\ValueObject\CustomerId;

final class CustomerIdIsAlreadyUsed extends \DomainException
{
    public function __construct(CustomerId $customerId)
    {
        parent::__construct("Customer id ($customerId) is already used");
    }
}
