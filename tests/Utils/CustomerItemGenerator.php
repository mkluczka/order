<?php

declare(strict_types=1);

namespace Tests\Utils;

use Iteo\Customer\Domain\ValueObject\CustomerId;
use Ramsey\Uuid\Uuid;

final class CustomerItemGenerator
{
    public static function randomCustomerId(): CustomerId
    {
        return new CustomerId(Uuid::uuid4()->toString());
    }
}
