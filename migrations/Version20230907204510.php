<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230907204510 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', first_name VARCHAR(64) DEFAULT NULL, last_name VARCHAR(64) DEFAULT NULL, phone_number VARCHAR(32) DEFAULT NULL, email_address VARCHAR(64) DEFAULT NULL, company VARCHAR(64) DEFAULT NULL, country_code VARCHAR(3) NOT NULL, province_code VARCHAR(16) DEFAULT NULL, province_name VARCHAR(64) DEFAULT NULL, city VARCHAR(64) DEFAULT NULL, street VARCHAR(128) DEFAULT NULL, postcode VARCHAR(8) DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', dtype VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE driver (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', user_account_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX UNIQ_11667CD93C0C9956 (user_account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shipment (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', owner_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', billing_address_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', origin_address_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', destination_address_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_2CB20DC7E3C61F9 (owner_id), INDEX IDX_2CB20DC79D0C0E4 (billing_address_id), INDEX IDX_2CB20DC4C6CF538 (origin_address_id), INDEX IDX_2CB20DCA88E34C7 (destination_address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shipment_driver_bid (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', shipment_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', driver_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', price INT NOT NULL, currency_code VARCHAR(3) NOT NULL, title VARCHAR(64) DEFAULT NULL, description VARCHAR(1000) DEFAULT NULL, status VARCHAR(64) NOT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_9DD32C17BE036FC (shipment_id), INDEX IDX_9DD32C1C3423909 (driver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shipment_item (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', shipment_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', dimension_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', label VARCHAR(64) NOT NULL, description VARCHAR(255) DEFAULT NULL, quantity INT NOT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_1C573407BE036FC (shipment_id), INDEX IDX_1C57340277428AD (dimension_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shipment_item_dimension (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', owner_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', length INT NOT NULL, width INT NOT NULL, height INT NOT NULL, unit VARCHAR(16) NOT NULL, INDEX IDX_44A2DAD67E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, first_name VARCHAR(64) NOT NULL, last_name VARCHAR(64) NOT NULL, phone VARCHAR(64) DEFAULT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_address (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', owner_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_5543718B7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE driver ADD CONSTRAINT FK_11667CD93C0C9956 FOREIGN KEY (user_account_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE shipment ADD CONSTRAINT FK_2CB20DC7E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE shipment ADD CONSTRAINT FK_2CB20DC79D0C0E4 FOREIGN KEY (billing_address_id) REFERENCES user_address (id)');
        $this->addSql('ALTER TABLE shipment ADD CONSTRAINT FK_2CB20DC4C6CF538 FOREIGN KEY (origin_address_id) REFERENCES user_address (id)');
        $this->addSql('ALTER TABLE shipment ADD CONSTRAINT FK_2CB20DCA88E34C7 FOREIGN KEY (destination_address_id) REFERENCES user_address (id)');
        $this->addSql('ALTER TABLE shipment_driver_bid ADD CONSTRAINT FK_9DD32C17BE036FC FOREIGN KEY (shipment_id) REFERENCES shipment (id)');
        $this->addSql('ALTER TABLE shipment_driver_bid ADD CONSTRAINT FK_9DD32C1C3423909 FOREIGN KEY (driver_id) REFERENCES driver (id)');
        $this->addSql('ALTER TABLE shipment_item ADD CONSTRAINT FK_1C573407BE036FC FOREIGN KEY (shipment_id) REFERENCES shipment (id)');
        $this->addSql('ALTER TABLE shipment_item ADD CONSTRAINT FK_1C57340277428AD FOREIGN KEY (dimension_id) REFERENCES shipment_item_dimension (id)');
        $this->addSql('ALTER TABLE shipment_item_dimension ADD CONSTRAINT FK_44A2DAD67E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user_address ADD CONSTRAINT FK_5543718B7E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user_address ADD CONSTRAINT FK_5543718BBF396750 FOREIGN KEY (id) REFERENCES address (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE driver DROP FOREIGN KEY FK_11667CD93C0C9956');
        $this->addSql('ALTER TABLE shipment DROP FOREIGN KEY FK_2CB20DC7E3C61F9');
        $this->addSql('ALTER TABLE shipment DROP FOREIGN KEY FK_2CB20DC79D0C0E4');
        $this->addSql('ALTER TABLE shipment DROP FOREIGN KEY FK_2CB20DC4C6CF538');
        $this->addSql('ALTER TABLE shipment DROP FOREIGN KEY FK_2CB20DCA88E34C7');
        $this->addSql('ALTER TABLE shipment_driver_bid DROP FOREIGN KEY FK_9DD32C17BE036FC');
        $this->addSql('ALTER TABLE shipment_driver_bid DROP FOREIGN KEY FK_9DD32C1C3423909');
        $this->addSql('ALTER TABLE shipment_item DROP FOREIGN KEY FK_1C573407BE036FC');
        $this->addSql('ALTER TABLE shipment_item DROP FOREIGN KEY FK_1C57340277428AD');
        $this->addSql('ALTER TABLE shipment_item_dimension DROP FOREIGN KEY FK_44A2DAD67E3C61F9');
        $this->addSql('ALTER TABLE user_address DROP FOREIGN KEY FK_5543718B7E3C61F9');
        $this->addSql('ALTER TABLE user_address DROP FOREIGN KEY FK_5543718BBF396750');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE driver');
        $this->addSql('DROP TABLE shipment');
        $this->addSql('DROP TABLE shipment_driver_bid');
        $this->addSql('DROP TABLE shipment_item');
        $this->addSql('DROP TABLE shipment_item_dimension');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE user_address');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
