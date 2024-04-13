<?php

declare(strict_types=1);

namespace Iteo\Client\Domain\ValueObject;

final readonly class ClientId implements \Stringable
{
    public function __construct(public string $value)
    {
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
