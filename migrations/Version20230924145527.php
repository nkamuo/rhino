<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230924145527 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE vehicle_dimension (id VARCHAR(255) NOT NULL, length INT NOT NULL, width INT NOT NULL, height INT NOT NULL, unit VARCHAR(32) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vehicle ADD dimension_id VARCHAR(255) DEFAULT NULL, ADD max_weight_capacity INT DEFAULT NULL, ADD note VARCHAR(255) DEFAULT NULL, ADD description LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E486277428AD FOREIGN KEY (dimension_id) REFERENCES vehicle_dimension (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1B80E486277428AD ON vehicle (dimension_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E486277428AD');
        $this->addSql('DROP TABLE vehicle_dimension');
        $this->addSql('DROP INDEX UNIQ_1B80E486277428AD ON vehicle');
        $this->addSql('ALTER TABLE vehicle DROP dimension_id, DROP max_weight_capacity, DROP note, DROP description');
    }
}
