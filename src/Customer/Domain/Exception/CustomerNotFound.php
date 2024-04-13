<?php

declare(strict_types=1);

namespace Iteo\Customer\Domain\Exception;

use Iteo\Customer\Domain\ValueObject\CustomerId;

final class CustomerNotFound extends \DomainException
{
    public function __construct(CustomerId $orderId)
    {
        parent::__construct("Customer with id ($orderId) was not found");
    }
}
