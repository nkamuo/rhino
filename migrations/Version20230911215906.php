<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230911215906 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shipment_budget (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', currency VARCHAR(3) NOT NULL, dtype VARCHAR(255) NOT NULL, price INT DEFAULT NULL, min_price INT DEFAULT NULL, max_price INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shipment ADD budget_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\'');
        $this->addSql('ALTER TABLE shipment ADD CONSTRAINT FK_2CB20DC36ABA6B8 FOREIGN KEY (budget_id) REFERENCES shipment_budget (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2CB20DC36ABA6B8 ON shipment (budget_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shipment DROP FOREIGN KEY FK_2CB20DC36ABA6B8');
        $this->addSql('DROP TABLE shipment_budget');
        $this->addSql('DROP INDEX UNIQ_2CB20DC36ABA6B8 ON shipment');
        $this->addSql('ALTER TABLE shipment DROP budget_id');
    }
}
