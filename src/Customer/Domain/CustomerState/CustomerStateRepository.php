<?php

declare(strict_types=1);

namespace Iteo\Customer\Domain\CustomerState;

interface CustomerStateRepository
{
    public function save(CustomerState $customerState): void;
}
