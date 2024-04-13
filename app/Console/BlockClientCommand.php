<?php

declare(strict_types=1);

namespace App\Console;

use Iteo\Client\Application\Command\BlockClient\BlockClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class BlockClientCommand extends Command
{
    public function __construct(private readonly MessageBusInterface $messageBus)
    {
        parent::__construct('app:queue:block-client');
    }

    protected function configure(): void
    {
        parent::configure();

        $this->addArgument('clientId', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->messageBus->dispatch(new BlockClient($input->getArgument('clientId')));

        $output->writeln('<info>OK</info>');

        return self::SUCCESS;
    }
}
