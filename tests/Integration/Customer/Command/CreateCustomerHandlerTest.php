<?php

declare(strict_types=1);

namespace Tests\Integration\Customer\Command;

use Iteo\Customer\Application\Command\CreateCustomer\CreateCustomer;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
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

    public function testCannotCreateSecondCustomerOnSameId(): void
    {
        $customerId = 'af64f9d9-3234-4b3e-9a42-ecd250383281';
        $this->dispatchCommand(new CreateCustomer($customerId, 0.));

        $this->expectException(HandlerFailedException::class);
        $this->expectExceptionMessageMatches("/\($customerId\) is already used/");

        $this->dispatchCommand(new CreateCustomer($customerId, 0.));
    }
}
