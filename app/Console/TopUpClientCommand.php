<?php

declare(strict_types=1);

namespace App\Console;

use Iteo\Client\Application\Command\TopUpClient\TopUpClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class TopUpClientCommand extends Command
{
    public function __construct(private readonly MessageBusInterface $messageBus)
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
        $this->messageBus->dispatch(
            new TopUpClient(
                $input->getArgument('clientId'),
                (float) $input->getArgument('additionalBalance')
            )
        );

        $output->writeln('<info>OK</info>');

        return self::SUCCESS;
    }
}
