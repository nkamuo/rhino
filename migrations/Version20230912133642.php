<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230912133642 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE route (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', code VARCHAR(64) NOT NULL, name VARCHAR(128) DEFAULT NULL, start_point POINT DEFAULT NULL COMMENT \'(DC2Type:Point)\', end_point POINT DEFAULT NULL COMMENT \'(DC2Type:Point)\', start_place_id VARCHAR(255) DEFAULT NULL, end_place_id VARCHAR(255) DEFAULT NULL, polyline LINESTRING NOT NULL COMMENT \'(DC2Type:LineString)\', distance INT DEFAULT NULL, duration INT DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE route_segment (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shipment (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', owner_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', billing_address_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', origin_address_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', destination_address_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', budget_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', route_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', type VARCHAR(64) NOT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status VARCHAR(64) DEFAULT \'PENDING\' NOT NULL, INDEX IDX_2CB20DC7E3C61F9 (owner_id), INDEX IDX_2CB20DC79D0C0E4 (billing_address_id), INDEX IDX_2CB20DC4C6CF538 (origin_address_id), INDEX IDX_2CB20DCA88E34C7 (destination_address_id), UNIQUE INDEX UNIQ_2CB20DC36ABA6B8 (budget_id), INDEX IDX_2CB20DC34ECB4E6 (route_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shipment_budget (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', currency VARCHAR(3) NOT NULL, dtype VARCHAR(255) NOT NULL, price INT DEFAULT NULL, min_price INT DEFAULT NULL, max_price INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shipment_driver_bid (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', shipment_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', driver_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', price INT NOT NULL, currency_code VARCHAR(3) NOT NULL, title VARCHAR(64) DEFAULT NULL, description VARCHAR(1000) DEFAULT NULL, status VARCHAR(64) NOT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_9DD32C17BE036FC (shipment_id), INDEX IDX_9DD32C1C3423909 (driver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shipment_item (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', shipment_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', product_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', quantity INT NOT NULL, description VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_1C573407BE036FC (shipment_id), INDEX IDX_1C573404584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shipment ADD CONSTRAINT FK_2CB20DC7E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE shipment ADD CONSTRAINT FK_2CB20DC79D0C0E4 FOREIGN KEY (billing_address_id) REFERENCES user_address (id)');
        $this->addSql('ALTER TABLE shipment ADD CONSTRAINT FK_2CB20DC4C6CF538 FOREIGN KEY (origin_address_id) REFERENCES user_address (id)');
        $this->addSql('ALTER TABLE shipment ADD CONSTRAINT FK_2CB20DCA88E34C7 FOREIGN KEY (destination_address_id) REFERENCES user_address (id)');
        $this->addSql('ALTER TABLE shipment ADD CONSTRAINT FK_2CB20DC36ABA6B8 FOREIGN KEY (budget_id) REFERENCES shipment_budget (id)');
        $this->addSql('ALTER TABLE shipment ADD CONSTRAINT FK_2CB20DC34ECB4E6 FOREIGN KEY (route_id) REFERENCES route (id)');
        $this->addSql('ALTER TABLE shipment_driver_bid ADD CONSTRAINT FK_9DD32C17BE036FC FOREIGN KEY (shipment_id) REFERENCES shipment (id)');
        $this->addSql('ALTER TABLE shipment_driver_bid ADD CONSTRAINT FK_9DD32C1C3423909 FOREIGN KEY (driver_id) REFERENCES driver (id)');
        $this->addSql('ALTER TABLE shipment_item ADD CONSTRAINT FK_1C573407BE036FC FOREIGN KEY (shipment_id) REFERENCES shipment (id)');
        $this->addSql('ALTER TABLE shipment_item ADD CONSTRAINT FK_1C573404584665A FOREIGN KEY (product_id) REFERENCES user_product (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shipment DROP FOREIGN KEY FK_2CB20DC7E3C61F9');
        $this->addSql('ALTER TABLE shipment DROP FOREIGN KEY FK_2CB20DC79D0C0E4');
        $this->addSql('ALTER TABLE shipment DROP FOREIGN KEY FK_2CB20DC4C6CF538');
        $this->addSql('ALTER TABLE shipment DROP FOREIGN KEY FK_2CB20DCA88E34C7');
        $this->addSql('ALTER TABLE shipment DROP FOREIGN KEY FK_2CB20DC36ABA6B8');
        $this->addSql('ALTER TABLE shipment DROP FOREIGN KEY FK_2CB20DC34ECB4E6');
        $this->addSql('ALTER TABLE shipment_driver_bid DROP FOREIGN KEY FK_9DD32C17BE036FC');
        $this->addSql('ALTER TABLE shipment_driver_bid DROP FOREIGN KEY FK_9DD32C1C3423909');
        $this->addSql('ALTER TABLE shipment_item DROP FOREIGN KEY FK_1C573407BE036FC');
        $this->addSql('ALTER TABLE shipment_item DROP FOREIGN KEY FK_1C573404584665A');
        $this->addSql('DROP TABLE route');
        $this->addSql('DROP TABLE route_segment');
        $this->addSql('DROP TABLE shipment');
        $this->addSql('DROP TABLE shipment_budget');
        $this->addSql('DROP TABLE shipment_driver_bid');
        $this->addSql('DROP TABLE shipment_item');
    }
}
