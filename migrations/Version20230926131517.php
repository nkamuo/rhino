<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230926131517 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shipment_order_activity (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', shipment_order_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', reference VARCHAR(32) NOT NULL, type VARCHAR(16) NOT NULL, label VARCHAR(64) NOT NULL, description LONGTEXT DEFAULT NULL, occured_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_CF6FE6512BC89259 (shipment_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shipment_order_activity ADD CONSTRAINT FK_CF6FE6512BC89259 FOREIGN KEY (shipment_order_id) REFERENCES shipment_order (id)');
        $this->addSql('ALTER TABLE shipment_order ADD expected_pickup_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD expected_delivery_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD activity_count INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shipment_order_activity DROP FOREIGN KEY FK_CF6FE6512BC89259');
        $this->addSql('DROP TABLE shipment_order_activity');
        $this->addSql('ALTER TABLE shipment_order DROP expected_pickup_at, DROP expected_delivery_at, DROP activity_count');
    }
}
