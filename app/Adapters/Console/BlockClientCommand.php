<?php

declare(strict_types=1);

namespace App\Adapters\Console;

use Iteo\Shared\Queue\Event\ClientBlockedDto;
use Iteo\Shared\Queue\QueueBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class BlockClientCommand extends Command
{
    public function __construct(private readonly QueueBus $queueBus)
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
        $this->queueBus->dispatch(new ClientBlockedDto($input->getArgument('clientId')));

        $output->writeln('<info>OK</info>');

        return self::SUCCESS;
    }
}
