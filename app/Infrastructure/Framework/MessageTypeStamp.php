<?php

declare(strict_types=1);

namespace App\Infrastructure\Framework;

use Symfony\Component\Messenger\Stamp\StampInterface;

final readonly class MessageTypeStamp implements StampInterface
{
    public function __construct(public string $type)
    {
    }
}
