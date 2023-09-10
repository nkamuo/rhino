<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230910174541 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shipment_item DROP FOREIGN KEY FK_1C57340277428AD');
        $this->addSql('CREATE TABLE address (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', coordinate_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', first_name VARCHAR(64) DEFAULT NULL, last_name VARCHAR(64) DEFAULT NULL, phone_number VARCHAR(32) DEFAULT NULL, email_address VARCHAR(64) DEFAULT NULL, company VARCHAR(64) DEFAULT NULL, country_code VARCHAR(3) NOT NULL, province_code VARCHAR(16) DEFAULT NULL, province_name VARCHAR(64) DEFAULT NULL, city VARCHAR(64) DEFAULT NULL, street VARCHAR(128) DEFAULT NULL, postcode VARCHAR(8) DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', google_id VARCHAR(255) DEFAULT NULL, formatted VARCHAR(255) DEFAULT NULL, dtype VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_D4E6F8198BBE953 (coordinate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE coordinate (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, altitude DOUBLE PRECISION DEFAULT NULL, accuracy DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', price_id INT DEFAULT NULL, dimension_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', title VARCHAR(64) NOT NULL, description VARCHAR(255) DEFAULT NULL, dtype VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_D34A04ADD614C7E7 (price_id), UNIQUE INDEX UNIQ_D34A04AD277428AD (dimension_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_dimension (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', length INT NOT NULL, width INT NOT NULL, height INT NOT NULL, unit VARCHAR(16) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_price (id INT AUTO_INCREMENT NOT NULL, currency VARCHAR(3) NOT NULL, amount INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_product (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', owner_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', INDEX IDX_8B471AA77E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F8198BBE953 FOREIGN KEY (coordinate_id) REFERENCES coordinate (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADD614C7E7 FOREIGN KEY (price_id) REFERENCES product_price (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD277428AD FOREIGN KEY (dimension_id) REFERENCES product_dimension (id)');
        $this->addSql('ALTER TABLE user_product ADD CONSTRAINT FK_8B471AA77E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user_product ADD CONSTRAINT FK_8B471AA7BF396750 FOREIGN KEY (id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shipment_item_dimension DROP FOREIGN KEY FK_44A2DAD67E3C61F9');
        $this->addSql('DROP TABLE shipment_item_dimension');
        $this->addSql('ALTER TABLE driver CHANGE id id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', CHANGE user_account_id user_account_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\'');
        $this->addSql('ALTER TABLE shipment CHANGE id id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', CHANGE owner_id owner_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', CHANGE billing_address_id billing_address_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', CHANGE origin_address_id origin_address_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', CHANGE destination_address_id destination_address_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\'');
        $this->addSql('ALTER TABLE shipment_driver_bid CHANGE id id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', CHANGE shipment_id shipment_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', CHANGE driver_id driver_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\'');
        $this->addSql('DROP INDEX IDX_1C57340277428AD ON shipment_item');
        $this->addSql('ALTER TABLE shipment_item ADD product_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', DROP dimension_id, DROP label, CHANGE id id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', CHANGE shipment_id shipment_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\'');
        $this->addSql('ALTER TABLE shipment_item ADD CONSTRAINT FK_1C573404584665A FOREIGN KEY (product_id) REFERENCES user_product (id)');
        $this->addSql('CREATE INDEX IDX_1C573404584665A ON shipment_item (product_id)');
        $this->addSql('ALTER TABLE user CHANGE id id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\'');
        $this->addSql('ALTER TABLE user_address CHANGE id id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', CHANGE owner_id owner_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_address DROP FOREIGN KEY FK_5543718BBF396750');
        $this->addSql('ALTER TABLE shipment_item DROP FOREIGN KEY FK_1C573404584665A');
        $this->addSql('CREATE TABLE shipment_item_dimension (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', owner_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', length INT NOT NULL, width INT NOT NULL, height INT NOT NULL, unit VARCHAR(16) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_44A2DAD67E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE shipment_item_dimension ADD CONSTRAINT FK_44A2DAD67E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F8198BBE953');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADD614C7E7');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD277428AD');
        $this->addSql('ALTER TABLE user_product DROP FOREIGN KEY FK_8B471AA77E3C61F9');
        $this->addSql('ALTER TABLE user_product DROP FOREIGN KEY FK_8B471AA7BF396750');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE coordinate');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_dimension');
        $this->addSql('DROP TABLE product_price');
        $this->addSql('DROP TABLE user_product');
        $this->addSql('ALTER TABLE shipment_driver_bid CHANGE id id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', CHANGE shipment_id shipment_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', CHANGE driver_id driver_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE driver CHANGE id id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', CHANGE user_account_id user_account_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('DROP INDEX IDX_1C573404584665A ON shipment_item');
        $this->addSql('ALTER TABLE shipment_item ADD dimension_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', ADD label VARCHAR(64) NOT NULL, DROP product_id, CHANGE id id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', CHANGE shipment_id shipment_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE shipment_item ADD CONSTRAINT FK_1C57340277428AD FOREIGN KEY (dimension_id) REFERENCES shipment_item_dimension (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_1C57340277428AD ON shipment_item (dimension_id)');
        $this->addSql('ALTER TABLE user_address CHANGE id id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', CHANGE owner_id owner_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE shipment CHANGE id id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', CHANGE owner_id owner_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', CHANGE billing_address_id billing_address_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE origin_address_id origin_address_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE destination_address_id destination_address_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE `user` CHANGE id id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
    }
}
