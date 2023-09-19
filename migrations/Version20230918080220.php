<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230918080220 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', coordinate_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', first_name VARCHAR(64) DEFAULT NULL, last_name VARCHAR(64) DEFAULT NULL, phone_number VARCHAR(32) DEFAULT NULL, email_address VARCHAR(64) DEFAULT NULL, company VARCHAR(64) DEFAULT NULL, country_code VARCHAR(3) NOT NULL, province_code VARCHAR(16) DEFAULT NULL, province_name VARCHAR(64) DEFAULT NULL, city VARCHAR(64) DEFAULT NULL, street VARCHAR(128) DEFAULT NULL, postcode VARCHAR(8) DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', google_id VARCHAR(255) DEFAULT NULL, formatted VARCHAR(255) DEFAULT NULL, arrive_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', dtype VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_D4E6F8198BBE953 (coordinate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE address_coordinates (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, altitude DOUBLE PRECISION DEFAULT NULL, accuracy DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE driver (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', user_account_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', verified TINYINT(1) DEFAULT 1 NOT NULL, UNIQUE INDEX UNIQ_11667CD93C0C9956 (user_account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', price_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', dimension_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', title VARCHAR(64) NOT NULL, description VARCHAR(255) DEFAULT NULL, weight INT DEFAULT 0 NOT NULL, dtype VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_D34A04ADD614C7E7 (price_id), UNIQUE INDEX UNIQ_D34A04AD277428AD (dimension_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_category (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', code VARCHAR(32) NOT NULL, name VARCHAR(64) NOT NULL, short_name VARCHAR(16) DEFAULT NULL, icon_image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_dimension (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', length INT NOT NULL, width INT NOT NULL, height INT NOT NULL, unit VARCHAR(16) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_price (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', currency VARCHAR(3) NOT NULL, amount INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE route (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', code VARCHAR(64) NOT NULL, name VARCHAR(128) DEFAULT NULL, start_point POINT DEFAULT NULL COMMENT \'(DC2Type:Point)\', end_point POINT DEFAULT NULL COMMENT \'(DC2Type:Point)\', start_place_id VARCHAR(255) DEFAULT NULL, end_place_id VARCHAR(255) DEFAULT NULL, polyline LINESTRING NOT NULL COMMENT \'(DC2Type:LineString)\', distance INT DEFAULT NULL, duration INT DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE route_segment (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shipment (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', owner_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', billing_address_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', origin_address_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', destination_address_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', budget_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', route_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', type VARCHAR(64) NOT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status VARCHAR(64) DEFAULT \'PENDING\' NOT NULL, pickup_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivery_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', code VARCHAR(32) NOT NULL, INDEX IDX_2CB20DC7E3C61F9 (owner_id), INDEX IDX_2CB20DC79D0C0E4 (billing_address_id), INDEX IDX_2CB20DC4C6CF538 (origin_address_id), INDEX IDX_2CB20DCA88E34C7 (destination_address_id), UNIQUE INDEX UNIQ_2CB20DC36ABA6B8 (budget_id), INDEX IDX_2CB20DC34ECB4E6 (route_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shipment_budget (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', currency VARCHAR(3) NOT NULL, dtype VARCHAR(255) NOT NULL, price INT DEFAULT NULL, min_price INT DEFAULT NULL, max_price INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shipment_driver_bid (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', shipment_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', driver_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', price INT NOT NULL, currency_code VARCHAR(3) NOT NULL, title VARCHAR(64) DEFAULT NULL, description VARCHAR(1000) DEFAULT NULL, status VARCHAR(64) NOT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_9DD32C17BE036FC (shipment_id), INDEX IDX_9DD32C1C3423909 (driver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shipment_item (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', shipment_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', product_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', quantity INT NOT NULL, description VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_1C573407BE036FC (shipment_id), INDEX IDX_1C573404584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, first_name VARCHAR(64) NOT NULL, last_name VARCHAR(64) NOT NULL, phone VARCHAR(64) DEFAULT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_address (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', owner_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', INDEX IDX_5543718B7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_product (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', owner_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', INDEX IDX_8B471AA77E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vehicle (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', type_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', driver_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', status VARCHAR(32) NOT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_1B80E486C54C8C93 (type_id), INDEX IDX_1B80E486C3423909 (driver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vehicle_type (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', code VARCHAR(32) NOT NULL, short_name VARCHAR(12) NOT NULL, name VARCHAR(64) NOT NULL, icon_image VARCHAR(255) DEFAULT NULL, primary_image VARCHAR(255) DEFAULT NULL, cover_image VARCHAR(255) DEFAULT NULL, client_note VARCHAR(255) DEFAULT NULL, driver_note VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F8198BBE953 FOREIGN KEY (coordinate_id) REFERENCES address_coordinates (id)');
        $this->addSql('ALTER TABLE driver ADD CONSTRAINT FK_11667CD93C0C9956 FOREIGN KEY (user_account_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADD614C7E7 FOREIGN KEY (price_id) REFERENCES product_price (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD277428AD FOREIGN KEY (dimension_id) REFERENCES product_dimension (id)');
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
        $this->addSql('ALTER TABLE user_address ADD CONSTRAINT FK_5543718B7E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user_address ADD CONSTRAINT FK_5543718BBF396750 FOREIGN KEY (id) REFERENCES address (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_product ADD CONSTRAINT FK_8B471AA77E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user_product ADD CONSTRAINT FK_8B471AA7BF396750 FOREIGN KEY (id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E486C54C8C93 FOREIGN KEY (type_id) REFERENCES vehicle_type (id)');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E486C3423909 FOREIGN KEY (driver_id) REFERENCES driver (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F8198BBE953');
        $this->addSql('ALTER TABLE driver DROP FOREIGN KEY FK_11667CD93C0C9956');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADD614C7E7');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD277428AD');
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
        $this->addSql('ALTER TABLE user_address DROP FOREIGN KEY FK_5543718B7E3C61F9');
        $this->addSql('ALTER TABLE user_address DROP FOREIGN KEY FK_5543718BBF396750');
        $this->addSql('ALTER TABLE user_product DROP FOREIGN KEY FK_8B471AA77E3C61F9');
        $this->addSql('ALTER TABLE user_product DROP FOREIGN KEY FK_8B471AA7BF396750');
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E486C54C8C93');
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E486C3423909');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE address_coordinates');
        $this->addSql('DROP TABLE driver');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_category');
        $this->addSql('DROP TABLE product_dimension');
        $this->addSql('DROP TABLE product_price');
        $this->addSql('DROP TABLE route');
        $this->addSql('DROP TABLE route_segment');
        $this->addSql('DROP TABLE shipment');
        $this->addSql('DROP TABLE shipment_budget');
        $this->addSql('DROP TABLE shipment_driver_bid');
        $this->addSql('DROP TABLE shipment_item');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE user_address');
        $this->addSql('DROP TABLE user_product');
        $this->addSql('DROP TABLE vehicle');
        $this->addSql('DROP TABLE vehicle_type');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
