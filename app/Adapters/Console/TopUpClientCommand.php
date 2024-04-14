<?php

declare(strict_types=1);

namespace App\Adapters\Console;

use Iteo\Shared\Queue\Event\ClientToppedUpDto;
use Iteo\Shared\Queue\QueueBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class TopUpClientCommand extends Command
{
    public function __construct(private readonly QueueBus $queueBus)
    {
        parent::__construct('app:queue:top-up-client');
    }

    protected function configure(): void
    {
        parent::configure();

        $this->addArgument('clientId', InputArgument::REQUIRED);
        $this->addArgument('additionalBalance', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $clientId = $input->getArgument('clientId');
        $additionalAmount = (float) $input->getArgument('additionalBalance');

        $this->queueBus->dispatch(
            new ClientToppedUpDto(
                $clientId,
                $additionalAmount
            )
        );

        $output->writeln("Client <comment>$clientId</comment> was topped up with <comment>$additionalAmount</comment> amount");

        return self::SUCCESS;
    }
}
