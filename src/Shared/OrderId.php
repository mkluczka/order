<?php

declare(strict_types=1);

namespace Iteo\Shared;

final readonly class OrderId implements \Stringable
{
    public function __construct(public string $value)
    {
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
