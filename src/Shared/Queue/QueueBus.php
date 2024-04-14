<?php

declare(strict_types=1);

namespace Iteo\Shared\Queue;

interface QueueBus
{
    public function dispatch(object $message): void;
}
