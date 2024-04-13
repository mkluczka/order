<?php

declare(strict_types=1);

namespace Tests\Integration\Customer\Command;

use Iteo\Customer\Application\Command\CreateCustomer\CreateCustomer;
use Tests\IntegrationTestCase;
use Tests\Utils\CustomerEntityAssertions;

final class CreateCustomerHandlerTest extends IntegrationTestCase
{
    use CustomerEntityAssertions;

    public function testCreateCustomer(): void
    {
        $customerId = '0b1e3cd3-e806-40b5-8a7e-05b2baa64977';
        $initialBalance = 0.0;

        $this->dispatchCommand(new CreateCustomer($customerId, $initialBalance));

        $this->assertCustomerInDatabase($customerId, $initialBalance);
    }
}
