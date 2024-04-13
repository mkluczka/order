<?php

declare(strict_types=1);

namespace Tests\Integration\Client\Command;

use Iteo\Client\Application\Command\CreateClient\CreateClient;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Tests\IntegrationTestCase;
use Tests\Utils\EntityAssertions;

final class CreateClientHandlerTest extends IntegrationTestCase
{
    use EntityAssertions;

    public function testCreateClient(): void
    {
        $clientId = '0b1e3cd3-e806-40b5-8a7e-05b2baa64977';
        $initialBalance = 0.0;

        $this->dispatchCommand(new CreateClient($clientId, $initialBalance));

        $this->assertClientInDatabase($clientId, $initialBalance);
    }

    public function testCannotCreateSecondClientOnSameId(): void
    {
        $clientId = 'af64f9d9-3234-4b3e-9a42-ecd250383281';
        $this->dispatchCommand(new CreateClient($clientId, 0.));

        $this->expectException(HandlerFailedException::class);
        $this->expectExceptionMessageMatches("/\($clientId\) is already used/");

        $this->dispatchCommand(new CreateClient($clientId, 0.));
    }
}
