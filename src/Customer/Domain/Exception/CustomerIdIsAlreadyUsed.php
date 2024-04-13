<?php

declare(strict_types=1);

namespace Iteo\Customer\Domain\Exception;

use Iteo\Customer\Domain\ValueObject\CustomerId;

final class CustomerIdIsAlreadyUsed extends \DomainException
{
    public function __construct(CustomerId $orderId)
    {
        parent::__construct("Customer id ($orderId) is already used");
    }
}
