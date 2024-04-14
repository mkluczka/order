<?php

declare(strict_types=1);

namespace Tests\Integration\Client\Command;

use Iteo\Client\Application\Create\Create;
use Iteo\Client\Application\TopUp\TopUp;
use Tests\AppTestCase;
use Tests\Utils\EntityAssertions;

final class TopUpClientHandlerTest extends AppTestCase
{
    use EntityAssertions;

    public function testTopUpClient(): void
    {
        $clientId = '8fc0371e-7dcb-4afe-b31f-98a3d76ef578';
        $additionalAmount = 33.22;

        $this->dispatchMessage(new Create($clientId, 0.0));

        $this->dispatchMessage(new TopUp($clientId, $additionalAmount));

        $this->assertClientInDatabase($clientId, $additionalAmount);
    }
}
