<?php

declare(strict_types=1);

namespace Tests\Functional\Console;

use Iteo\Client\Application\Create\CreateClient;
use Tests\ConsoleCommandTest;
use Tests\Utils\EntityAssertions;

final class BlockClientCommandTest extends ConsoleCommandTest
{
    use EntityAssertions;

    public function testBlockClientCommand(): void
    {
        $clientId = 'df71763c-406f-415f-9c70-f23b0766a7ea';

        $this->dispatchMessage(new CreateClient($clientId, 'test', 11.11));

        $this->testCliCommand('app:queue:block-client', [
            'clientId' => $clientId,
        ]);

        $this->assertClientInDatabase($clientId, 11.11, true);
    }
}
