<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240413154048 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE audit_log (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, message VARCHAR(255) NOT NULL, payload CLOB DEFAULT NULL --(DC2Type:json)
        )');
        $this->addSql('CREATE TABLE clients (id CHAR(36) NOT NULL --(DC2Type:guid)
        , balance DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "orders" (id CHAR(36) NOT NULL --(DC2Type:guid)
        , client_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , PRIMARY KEY(id), CONSTRAINT FK_E52FFDEE19EB6921 FOREIGN KEY (client_id) REFERENCES clients (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_E52FFDEE19EB6921 ON "orders" (client_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE audit_log');
        $this->addSql('DROP TABLE clients');
        $this->addSql('DROP TABLE "orders"');
    }
}
