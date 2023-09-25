<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230924163712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE vehicle (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', type_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', driver_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', dimension_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', status VARCHAR(32) NOT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', vin VARCHAR(32) NOT NULL, license_plate_number VARCHAR(16) NOT NULL, max_weight_capacity INT DEFAULT NULL, note VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_1B80E486C54C8C93 (type_id), INDEX IDX_1B80E486C3423909 (driver_id), UNIQUE INDEX UNIQ_1B80E486277428AD (dimension_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vehicle_dimension (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', length INT NOT NULL, width INT NOT NULL, height INT NOT NULL, unit VARCHAR(32) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E486C54C8C93 FOREIGN KEY (type_id) REFERENCES vehicle_type (id)');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E486C3423909 FOREIGN KEY (driver_id) REFERENCES driver (id)');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E486277428AD FOREIGN KEY (dimension_id) REFERENCES vehicle_dimension (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E486C54C8C93');
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E486C3423909');
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E486277428AD');
        $this->addSql('DROP TABLE vehicle');
        $this->addSql('DROP TABLE vehicle_dimension');
    }
}
