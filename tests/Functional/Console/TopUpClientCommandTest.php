<?php

declare(strict_types=1);

namespace Tests\Functional\Console;

use Iteo\Client\Application\Create\CreateClient;
use Tests\ConsoleCommandTest;
use Tests\Utils\EntityAssertions;

final class TopUpClientCommandTest extends ConsoleCommandTest
{
    use EntityAssertions;

    public function testBlockClientCommand(): void
    {
        $clientId = 'df71763c-406f-415f-9c70-f23b0766a7ea';
        $additionalBalance = 43.21;

        $this->dispatchMessage(new CreateClient($clientId, 0.0));

        $this->testCliCommand('app:queue:top-up-client', [
            'clientId' => $clientId,
            'additionalBalance' => "$additionalBalance",
        ]);

        $this->assertClientInDatabase($clientId, $additionalBalance);
    }
}
