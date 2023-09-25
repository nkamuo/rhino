<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230925091626 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shipment_driver_bid (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', shipment_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', driver_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', vehicle_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', price_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', title VARCHAR(64) DEFAULT NULL, description VARCHAR(1000) DEFAULT NULL, status VARCHAR(64) NOT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', pickup_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivery_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_9DD32C17BE036FC (shipment_id), INDEX IDX_9DD32C1C3423909 (driver_id), INDEX IDX_9DD32C1545317D1 (vehicle_id), UNIQUE INDEX UNIQ_9DD32C1D614C7E7 (price_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shipment_driver_bid_price (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', currency VARCHAR(3) NOT NULL, amount INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shipment_driver_bid ADD CONSTRAINT FK_9DD32C17BE036FC FOREIGN KEY (shipment_id) REFERENCES shipment (id)');
        $this->addSql('ALTER TABLE shipment_driver_bid ADD CONSTRAINT FK_9DD32C1C3423909 FOREIGN KEY (driver_id) REFERENCES driver (id)');
        $this->addSql('ALTER TABLE shipment_driver_bid ADD CONSTRAINT FK_9DD32C1545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('ALTER TABLE shipment_driver_bid ADD CONSTRAINT FK_9DD32C1D614C7E7 FOREIGN KEY (price_id) REFERENCES shipment_driver_bid_price (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shipment_driver_bid DROP FOREIGN KEY FK_9DD32C17BE036FC');
        $this->addSql('ALTER TABLE shipment_driver_bid DROP FOREIGN KEY FK_9DD32C1C3423909');
        $this->addSql('ALTER TABLE shipment_driver_bid DROP FOREIGN KEY FK_9DD32C1545317D1');
        $this->addSql('ALTER TABLE shipment_driver_bid DROP FOREIGN KEY FK_9DD32C1D614C7E7');
        $this->addSql('DROP TABLE shipment_driver_bid');
        $this->addSql('DROP TABLE shipment_driver_bid_price');
    }
}
