<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230927162021 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shipment_document (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shipment_document_attachment (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', document_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', type VARCHAR(32) NOT NULL, src VARCHAR(64) NOT NULL, caption VARCHAR(255) DEFAULT NULL, meta LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', INDEX IDX_D2902EB5C33F7837 (document_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shipment_proof_document (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shipment_document_attachment ADD CONSTRAINT FK_D2902EB5C33F7837 FOREIGN KEY (document_id) REFERENCES shipment_document (id)');
        $this->addSql('ALTER TABLE shipment_order ADD pickup_confirmation_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', ADD proof_of_delivery_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\'');
        $this->addSql('ALTER TABLE shipment_order ADD CONSTRAINT FK_79E4313D68326688 FOREIGN KEY (pickup_confirmation_id) REFERENCES shipment_document (id)');
        $this->addSql('ALTER TABLE shipment_order ADD CONSTRAINT FK_79E4313D9B5B8FB1 FOREIGN KEY (proof_of_delivery_id) REFERENCES shipment_document (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_79E4313D68326688 ON shipment_order (pickup_confirmation_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_79E4313D9B5B8FB1 ON shipment_order (proof_of_delivery_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shipment_order DROP FOREIGN KEY FK_79E4313D68326688');
        $this->addSql('ALTER TABLE shipment_order DROP FOREIGN KEY FK_79E4313D9B5B8FB1');
        $this->addSql('ALTER TABLE shipment_document_attachment DROP FOREIGN KEY FK_D2902EB5C33F7837');
        $this->addSql('DROP TABLE shipment_document');
        $this->addSql('DROP TABLE shipment_document_attachment');
        $this->addSql('DROP TABLE shipment_proof_document');
        $this->addSql('DROP INDEX UNIQ_79E4313D68326688 ON shipment_order');
        $this->addSql('DROP INDEX UNIQ_79E4313D9B5B8FB1 ON shipment_order');
        $this->addSql('ALTER TABLE shipment_order DROP pickup_confirmation_id, DROP proof_of_delivery_id');
    }
}
