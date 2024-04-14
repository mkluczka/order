<?php

declare(strict_types=1);

namespace Iteo\Shared;

interface CommandBus
{
    public function dispatch(object $command): void;
}
