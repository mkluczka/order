<?php

declare(strict_types=1);

namespace Iteo\Shared;

final readonly class ClientId implements \Stringable,\JsonSerializable
{
    public function __construct(public string $value)
    {
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function jsonSerialize(): string
    {
        return (string)$this;
    }
}
