<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230926195133 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shipment_order_charge (id INT AUTO_INCREMENT NOT NULL, shipment_order_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', label VARCHAR(32) NOT NULL, amount INT NOT NULL, type VARCHAR(16) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_61A9DD242BC89259 (shipment_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shipment_order_charge ADD CONSTRAINT FK_61A9DD242BC89259 FOREIGN KEY (shipment_order_id) REFERENCES shipment_order (id)');
        $this->addSql('ALTER TABLE shipment_order ADD code VARCHAR(32) NOT NULL, ADD currency VARCHAR(3) NOT NULL, ADD subtotal INT NOT NULL, ADD charge_total INT NOT NULL, ADD total INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shipment_order_charge DROP FOREIGN KEY FK_61A9DD242BC89259');
        $this->addSql('DROP TABLE shipment_order_charge');
        $this->addSql('ALTER TABLE shipment_order DROP code, DROP currency, DROP subtotal, DROP charge_total, DROP total');
    }
}
