<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240414075158 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE clients ADD COLUMN is_blocked BOOLEAN NOT NULL');
        $this->addSql('CREATE TEMPORARY TABLE __temp__orders AS SELECT id, client_id FROM orders');
        $this->addSql('DROP TABLE orders');
        $this->addSql('CREATE TABLE orders (id CHAR(36) NOT NULL --(DC2Type:guid)
        , client_id CHAR(36) NOT NULL --(DC2Type:guid)
        , PRIMARY KEY(id))');
        $this->addSql('INSERT INTO orders (id, client_id) SELECT id, client_id FROM __temp__orders');
        $this->addSql('DROP TABLE __temp__orders');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__clients AS SELECT id, balance FROM clients');
        $this->addSql('DROP TABLE clients');
        $this->addSql('CREATE TABLE clients (id CHAR(36) NOT NULL --(DC2Type:guid)
        , balance DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO clients (id, balance) SELECT id, balance FROM __temp__clients');
        $this->addSql('DROP TABLE __temp__clients');
        $this->addSql('CREATE TEMPORARY TABLE __temp__orders AS SELECT id, client_id FROM "orders"');
        $this->addSql('DROP TABLE "orders"');
        $this->addSql('CREATE TABLE "orders" (id CHAR(36) NOT NULL --(DC2Type:guid)
        , client_id CHAR(36) DEFAULT NULL --(DC2Type:guid)
        , PRIMARY KEY(id), CONSTRAINT FK_E52FFDEE19EB6921 FOREIGN KEY (client_id) REFERENCES clients (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO "orders" (id, client_id) SELECT id, client_id FROM __temp__orders');
        $this->addSql('DROP TABLE __temp__orders');
        $this->addSql('CREATE INDEX IDX_E52FFDEE19EB6921 ON "orders" (client_id)');
    }
}
