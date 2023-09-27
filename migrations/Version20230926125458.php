<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230926125458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shipment_execution (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', driver_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', vehicle_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', starting_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', label VARCHAR(64) DEFAULT NULL, description LONGTEXT DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_38B3D10BC3423909 (driver_id), INDEX IDX_38B3D10B545317D1 (vehicle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shipment_order (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', driver_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', shipper_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', shipment_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', bid_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', vehicle_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', execution_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', status VARCHAR(32) NOT NULL, pickup_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivery_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_79E4313DC3423909 (driver_id), INDEX IDX_79E4313D38459F23 (shipper_id), UNIQUE INDEX UNIQ_79E4313D7BE036FC (shipment_id), UNIQUE INDEX UNIQ_79E4313D4D9866B8 (bid_id), INDEX IDX_79E4313D545317D1 (vehicle_id), INDEX IDX_79E4313D57125544 (execution_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shipment_execution ADD CONSTRAINT FK_38B3D10BC3423909 FOREIGN KEY (driver_id) REFERENCES driver (id)');
        $this->addSql('ALTER TABLE shipment_execution ADD CONSTRAINT FK_38B3D10B545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('ALTER TABLE shipment_order ADD CONSTRAINT FK_79E4313DC3423909 FOREIGN KEY (driver_id) REFERENCES driver (id)');
        $this->addSql('ALTER TABLE shipment_order ADD CONSTRAINT FK_79E4313D38459F23 FOREIGN KEY (shipper_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE shipment_order ADD CONSTRAINT FK_79E4313D7BE036FC FOREIGN KEY (shipment_id) REFERENCES shipment (id)');
        $this->addSql('ALTER TABLE shipment_order ADD CONSTRAINT FK_79E4313D4D9866B8 FOREIGN KEY (bid_id) REFERENCES shipment_driver_bid (id)');
        $this->addSql('ALTER TABLE shipment_order ADD CONSTRAINT FK_79E4313D545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('ALTER TABLE shipment_order ADD CONSTRAINT FK_79E4313D57125544 FOREIGN KEY (execution_id) REFERENCES shipment_execution (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shipment_execution DROP FOREIGN KEY FK_38B3D10BC3423909');
        $this->addSql('ALTER TABLE shipment_execution DROP FOREIGN KEY FK_38B3D10B545317D1');
        $this->addSql('ALTER TABLE shipment_order DROP FOREIGN KEY FK_79E4313DC3423909');
        $this->addSql('ALTER TABLE shipment_order DROP FOREIGN KEY FK_79E4313D38459F23');
        $this->addSql('ALTER TABLE shipment_order DROP FOREIGN KEY FK_79E4313D7BE036FC');
        $this->addSql('ALTER TABLE shipment_order DROP FOREIGN KEY FK_79E4313D4D9866B8');
        $this->addSql('ALTER TABLE shipment_order DROP FOREIGN KEY FK_79E4313D545317D1');
        $this->addSql('ALTER TABLE shipment_order DROP FOREIGN KEY FK_79E4313D57125544');
        $this->addSql('DROP TABLE shipment_execution');
        $this->addSql('DROP TABLE shipment_order');
    }
}
