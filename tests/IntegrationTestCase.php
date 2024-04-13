<?php

declare(strict_types=1);

namespace Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\MessageBusInterface;

abstract class IntegrationTestCase extends KernelTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();
    }

    protected function dispatchCommand(object $command): void
    {
        /** @var MessageBusInterface $commandBus */
        $commandBus = self::getContainer()->get(MessageBusInterface::class);

        $commandBus->dispatch($command);
    }
}
