<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240414103725 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE audit_log ADD COLUMN created_at VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__audit_log AS SELECT id, message, payload FROM audit_log');
        $this->addSql('DROP TABLE audit_log');
        $this->addSql('CREATE TABLE audit_log (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, message VARCHAR(255) NOT NULL, payload CLOB DEFAULT NULL --(DC2Type:json)
        )');
        $this->addSql('INSERT INTO audit_log (id, message, payload) SELECT id, message, payload FROM __temp__audit_log');
        $this->addSql('DROP TABLE __temp__audit_log');
    }
}
