<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231005203908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE driver_address (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', company VARCHAR(64) DEFAULT NULL, street VARCHAR(128) DEFAULT NULL, city VARCHAR(64) DEFAULT NULL, province_code VARCHAR(8) DEFAULT NULL, province_name VARCHAR(64) DEFAULT NULL, postcode VARCHAR(12) NOT NULL, country_code VARCHAR(3) NOT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE driver ADD address_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', ADD gender VARCHAR(32) DEFAULT NULL, ADD dob DATE DEFAULT NULL, CHANGE verified verified TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE driver ADD CONSTRAINT FK_11667CD9F5B7AF75 FOREIGN KEY (address_id) REFERENCES driver_address (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_11667CD9F5B7AF75 ON driver (address_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE driver DROP FOREIGN KEY FK_11667CD9F5B7AF75');
        $this->addSql('DROP TABLE driver_address');
        $this->addSql('DROP INDEX UNIQ_11667CD9F5B7AF75 ON driver');
        $this->addSql('ALTER TABLE driver DROP address_id, DROP gender, DROP dob, CHANGE verified verified TINYINT(1) DEFAULT 1 NOT NULL');
    }
}
