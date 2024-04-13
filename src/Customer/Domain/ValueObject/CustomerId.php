<?php

declare(strict_types=1);

namespace Iteo\Customer\Domain\ValueObject;

final readonly class CustomerId implements \Stringable
{
    public function __construct(public string $value)
    {
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
