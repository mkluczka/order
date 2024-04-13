<?php

declare(strict_types=1);

namespace Tests;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

abstract class ConsoleCommandTest extends AppTestCase
{
    /**
     * @param array<string,string> $arguments
     */
    protected function testCliCommand(string $commandName, array $arguments): void
    {
        if (self::$kernel === null) {
            return;
        }

        $application = new Application(self::$kernel);

        $command = $application->find($commandName);
        $commandTester = new CommandTester($command);
        $commandTester->execute($arguments);

        $commandTester->assertCommandIsSuccessful();
    }
}
