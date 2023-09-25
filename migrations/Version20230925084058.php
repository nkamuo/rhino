<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230925084058 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shipment_driver_bid_price (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', currency_code VARCHAR(3) NOT NULL, amount INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shipment_driver_bid ADD vehicle_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', ADD price_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', ADD pickup_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD delivery_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP price, DROP currency_code');
        $this->addSql('ALTER TABLE shipment_driver_bid ADD CONSTRAINT FK_9DD32C1545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('ALTER TABLE shipment_driver_bid ADD CONSTRAINT FK_9DD32C1D614C7E7 FOREIGN KEY (price_id) REFERENCES shipment_budget (id)');
        $this->addSql('CREATE INDEX IDX_9DD32C1545317D1 ON shipment_driver_bid (vehicle_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9DD32C1D614C7E7 ON shipment_driver_bid (price_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE shipment_driver_bid_price');
        $this->addSql('ALTER TABLE shipment_driver_bid DROP FOREIGN KEY FK_9DD32C1545317D1');
        $this->addSql('ALTER TABLE shipment_driver_bid DROP FOREIGN KEY FK_9DD32C1D614C7E7');
        $this->addSql('DROP INDEX IDX_9DD32C1545317D1 ON shipment_driver_bid');
        $this->addSql('DROP INDEX UNIQ_9DD32C1D614C7E7 ON shipment_driver_bid');
        $this->addSql('ALTER TABLE shipment_driver_bid ADD price INT NOT NULL, ADD currency_code VARCHAR(3) NOT NULL, DROP vehicle_id, DROP price_id, DROP pickup_at, DROP delivery_at');
    }
}
