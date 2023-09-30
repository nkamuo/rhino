<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230930081522 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shipment_order_shipment_document (shipment_order_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', shipment_document_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', INDEX IDX_4D2269C72BC89259 (shipment_order_id), INDEX IDX_4D2269C7EC01E0A3 (shipment_document_id), PRIMARY KEY(shipment_order_id, shipment_document_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shipment_order_shipment_document ADD CONSTRAINT FK_4D2269C72BC89259 FOREIGN KEY (shipment_order_id) REFERENCES shipment_order (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shipment_order_shipment_document ADD CONSTRAINT FK_4D2269C7EC01E0A3 FOREIGN KEY (shipment_document_id) REFERENCES shipment_document (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shipment_document ADD meta LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', ADD type VARCHAR(32) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shipment_order_shipment_document DROP FOREIGN KEY FK_4D2269C72BC89259');
        $this->addSql('ALTER TABLE shipment_order_shipment_document DROP FOREIGN KEY FK_4D2269C7EC01E0A3');
        $this->addSql('DROP TABLE shipment_order_shipment_document');
        $this->addSql('ALTER TABLE shipment_document DROP meta, DROP type');
    }
}
