<?php

declare(strict_types=1);

namespace Iteo\Customer\Domain\ValueObject;

final readonly class CustomerId
{
    public function __construct(public string $value)
    {
    }
}
