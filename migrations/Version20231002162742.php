<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231002162742 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE frames_channel_participants (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_8F504A0BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE frames_channels (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE frames_message_attachments (id INT AUTO_INCREMENT NOT NULL, message_id INT NOT NULL, type VARCHAR(32) NOT NULL, uri VARCHAR(255) NOT NULL, caption VARCHAR(128) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_D340B496537A1329 (message_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE frames_messages (id INT AUTO_INCREMENT NOT NULL, sender_id INT NOT NULL, title VARCHAR(64) DEFAULT NULL, body LONGTEXT DEFAULT NULL, INDEX IDX_F6B8C09AF624B39D (sender_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE frames_users (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, short_name VARCHAR(16) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, rating SMALLINT NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unit_review (id INT AUTO_INCREMENT NOT NULL, review_id INT NOT NULL, rating SMALLINT NOT NULL, description VARCHAR(255) DEFAULT NULL, type VARCHAR(32) NOT NULL, INDEX IDX_EE1A6A303E2E969B (review_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE frames_channel_participants ADD CONSTRAINT FK_8F504A0BA76ED395 FOREIGN KEY (user_id) REFERENCES frames_users (id)');
        $this->addSql('ALTER TABLE frames_message_attachments ADD CONSTRAINT FK_D340B496537A1329 FOREIGN KEY (message_id) REFERENCES frames_messages (id)');
        $this->addSql('ALTER TABLE frames_messages ADD CONSTRAINT FK_F6B8C09AF624B39D FOREIGN KEY (sender_id) REFERENCES frames_channel_participants (id)');
        $this->addSql('ALTER TABLE unit_review ADD CONSTRAINT FK_EE1A6A303E2E969B FOREIGN KEY (review_id) REFERENCES review (id)');
        $this->addSql('ALTER TABLE shipment_order ADD review_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shipment_order ADD CONSTRAINT FK_79E4313D3E2E969B FOREIGN KEY (review_id) REFERENCES review (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_79E4313D3E2E969B ON shipment_order (review_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shipment_order DROP FOREIGN KEY FK_79E4313D3E2E969B');
        $this->addSql('ALTER TABLE frames_channel_participants DROP FOREIGN KEY FK_8F504A0BA76ED395');
        $this->addSql('ALTER TABLE frames_message_attachments DROP FOREIGN KEY FK_D340B496537A1329');
        $this->addSql('ALTER TABLE frames_messages DROP FOREIGN KEY FK_F6B8C09AF624B39D');
        $this->addSql('ALTER TABLE unit_review DROP FOREIGN KEY FK_EE1A6A303E2E969B');
        $this->addSql('DROP TABLE frames_channel_participants');
        $this->addSql('DROP TABLE frames_channels');
        $this->addSql('DROP TABLE frames_message_attachments');
        $this->addSql('DROP TABLE frames_messages');
        $this->addSql('DROP TABLE frames_users');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE unit_review');
        $this->addSql('DROP INDEX UNIQ_79E4313D3E2E969B ON shipment_order');
        $this->addSql('ALTER TABLE shipment_order DROP review_id');
    }
}
