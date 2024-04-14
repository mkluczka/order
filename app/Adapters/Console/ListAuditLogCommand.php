<?php

declare(strict_types=1);

namespace App\Adapters\Console;

use App\Infrastructure\Framework\ORM\Entity\AuditLogEntity;
use App\Infrastructure\Framework\ORM\Repository\AuditLogEntityRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ListAuditLogCommand extends Command
{
    public function __construct(private readonly AuditLogEntityRepository $auditLogEntityRepository)
    {
        parent::__construct('app:audit-log');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $auditLogs = $this->auditLogEntityRepository->findAll();

        $table = new Table($output);
        $table->setHeaders(['', 'Message', 'Payload', 'Created at']);
        $table->setStyle('box');
        $table->addRows(
            array_map(
                fn (AuditLogEntity $entity) => $entity->asArray(),
                $auditLogs
            )
        );

        $table->render();

        return self::SUCCESS;
    }
}
