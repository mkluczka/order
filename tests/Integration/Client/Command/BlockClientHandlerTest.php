<?php

declare(strict_types=1);

namespace Tests\Integration\Client\Command;

use Iteo\Client\Application\Block\Block;
use Iteo\Client\Application\Create\Create;
use Tests\AppTestCase;
use Tests\Utils\EntityAssertions;

final class BlockClientHandlerTest extends AppTestCase
{
    use EntityAssertions;

    public function testBlockClient(): void
    {
        $clientId = '7d9c5aa0-735d-4ff3-883f-9f346a9f3230';

        $this->dispatchMessage(new Create($clientId, 0.0));

        $this->dispatchMessage(new Block($clientId));

        $this->assertClientInDatabase($clientId, 0.0, true);
    }
}
