<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231015100336 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abstract_chat_channel (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', code VARCHAR(64) DEFAULT NULL, reference VARCHAR(255) NOT NULL, subject VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', participant_count INT DEFAULT 0 NOT NULL, title VARCHAR(64) DEFAULT NULL, subtitle VARCHAR(255) DEFAULT NULL, dtype VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE abstract_chat_participant (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', channel_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', role_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', sender_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', receiver_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', code VARCHAR(64) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', dtype VARCHAR(255) NOT NULL, business_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', name VARCHAR(128) DEFAULT NULL, user_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', INDEX IDX_85FAB64072F5A1AA (channel_id), INDEX IDX_85FAB640D60322AC (role_id), INDEX IDX_85FAB640F624B39D (sender_id), INDEX IDX_85FAB640CD53EDB6 (receiver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat_base_messages (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', title VARCHAR(100) DEFAULT NULL, body VARCHAR(4000) NOT NULL, sent_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', dtype VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat_channel_role (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', channel_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', code VARCHAR(64) NOT NULL, title VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', max_num_of_participant INT NOT NULL, INDEX IDX_35D6409572F5A1AA (channel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat_direct_messages (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', conversation_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', INDEX IDX_110C810D9AC0396 (conversation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat_message (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', channel_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', subject_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', participant_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', INDEX IDX_FAB3FC1672F5A1AA (channel_id), INDEX IDX_FAB3FC1623EDC87 (subject_id), INDEX IDX_FAB3FC169D1C3019 (participant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat_message_attachment (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', message_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', uri VARCHAR(255) NOT NULL, type VARCHAR(255) DEFAULT NULL, size INT DEFAULT NULL, INDEX IDX_4965BF86537A1329 (message_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat_message_view (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', message_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', participant_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', viewed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_A98359D6537A1329 (message_id), INDEX IDX_A98359D69D1C3019 (participant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat_subject (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', channel_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', title VARCHAR(64) DEFAULT NULL, subtitle VARCHAR(255) DEFAULT NULL, reference VARCHAR(255) DEFAULT NULL, INDEX IDX_B7C0F21372F5A1AA (channel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE frames_channel_participants (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_8F504A0BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE frames_channels (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE frames_message_attachments (id INT AUTO_INCREMENT NOT NULL, message_id INT NOT NULL, type VARCHAR(32) NOT NULL, uri VARCHAR(255) NOT NULL, caption VARCHAR(128) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_D340B496537A1329 (message_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE frames_messages (id INT AUTO_INCREMENT NOT NULL, sender_id INT NOT NULL, title VARCHAR(64) DEFAULT NULL, body LONGTEXT DEFAULT NULL, INDEX IDX_F6B8C09AF624B39D (sender_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE frames_users (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, short_name VARCHAR(16) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE abstract_chat_participant ADD CONSTRAINT FK_85FAB64072F5A1AA FOREIGN KEY (channel_id) REFERENCES abstract_chat_channel (id)');
        $this->addSql('ALTER TABLE abstract_chat_participant ADD CONSTRAINT FK_85FAB640D60322AC FOREIGN KEY (role_id) REFERENCES chat_channel_role (id)');
        $this->addSql('ALTER TABLE abstract_chat_participant ADD CONSTRAINT FK_85FAB640F624B39D FOREIGN KEY (sender_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE abstract_chat_participant ADD CONSTRAINT FK_85FAB640CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE chat_channel_role ADD CONSTRAINT FK_35D6409572F5A1AA FOREIGN KEY (channel_id) REFERENCES abstract_chat_channel (id)');
        $this->addSql('ALTER TABLE chat_direct_messages ADD CONSTRAINT FK_110C810D9AC0396 FOREIGN KEY (conversation_id) REFERENCES abstract_chat_participant (id)');
        $this->addSql('ALTER TABLE chat_direct_messages ADD CONSTRAINT FK_110C810DBF396750 FOREIGN KEY (id) REFERENCES chat_base_messages (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chat_message ADD CONSTRAINT FK_FAB3FC1672F5A1AA FOREIGN KEY (channel_id) REFERENCES abstract_chat_channel (id)');
        $this->addSql('ALTER TABLE chat_message ADD CONSTRAINT FK_FAB3FC1623EDC87 FOREIGN KEY (subject_id) REFERENCES chat_subject (id)');
        $this->addSql('ALTER TABLE chat_message ADD CONSTRAINT FK_FAB3FC169D1C3019 FOREIGN KEY (participant_id) REFERENCES abstract_chat_participant (id)');
        $this->addSql('ALTER TABLE chat_message ADD CONSTRAINT FK_FAB3FC16BF396750 FOREIGN KEY (id) REFERENCES chat_base_messages (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chat_message_attachment ADD CONSTRAINT FK_4965BF86537A1329 FOREIGN KEY (message_id) REFERENCES chat_base_messages (id)');
        $this->addSql('ALTER TABLE chat_message_view ADD CONSTRAINT FK_A98359D6537A1329 FOREIGN KEY (message_id) REFERENCES chat_message (id)');
        $this->addSql('ALTER TABLE chat_message_view ADD CONSTRAINT FK_A98359D69D1C3019 FOREIGN KEY (participant_id) REFERENCES abstract_chat_participant (id)');
        $this->addSql('ALTER TABLE chat_subject ADD CONSTRAINT FK_B7C0F21372F5A1AA FOREIGN KEY (channel_id) REFERENCES abstract_chat_channel (id)');
        $this->addSql('ALTER TABLE frames_channel_participants ADD CONSTRAINT FK_8F504A0BA76ED395 FOREIGN KEY (user_id) REFERENCES frames_users (id)');
        $this->addSql('ALTER TABLE frames_message_attachments ADD CONSTRAINT FK_D340B496537A1329 FOREIGN KEY (message_id) REFERENCES frames_messages (id)');
        $this->addSql('ALTER TABLE frames_messages ADD CONSTRAINT FK_F6B8C09AF624B39D FOREIGN KEY (sender_id) REFERENCES frames_channel_participants (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abstract_chat_participant DROP FOREIGN KEY FK_85FAB64072F5A1AA');
        $this->addSql('ALTER TABLE abstract_chat_participant DROP FOREIGN KEY FK_85FAB640D60322AC');
        $this->addSql('ALTER TABLE abstract_chat_participant DROP FOREIGN KEY FK_85FAB640F624B39D');
        $this->addSql('ALTER TABLE abstract_chat_participant DROP FOREIGN KEY FK_85FAB640CD53EDB6');
        $this->addSql('ALTER TABLE chat_channel_role DROP FOREIGN KEY FK_35D6409572F5A1AA');
        $this->addSql('ALTER TABLE chat_direct_messages DROP FOREIGN KEY FK_110C810D9AC0396');
        $this->addSql('ALTER TABLE chat_direct_messages DROP FOREIGN KEY FK_110C810DBF396750');
        $this->addSql('ALTER TABLE chat_message DROP FOREIGN KEY FK_FAB3FC1672F5A1AA');
        $this->addSql('ALTER TABLE chat_message DROP FOREIGN KEY FK_FAB3FC1623EDC87');
        $this->addSql('ALTER TABLE chat_message DROP FOREIGN KEY FK_FAB3FC169D1C3019');
        $this->addSql('ALTER TABLE chat_message DROP FOREIGN KEY FK_FAB3FC16BF396750');
        $this->addSql('ALTER TABLE chat_message_attachment DROP FOREIGN KEY FK_4965BF86537A1329');
        $this->addSql('ALTER TABLE chat_message_view DROP FOREIGN KEY FK_A98359D6537A1329');
        $this->addSql('ALTER TABLE chat_message_view DROP FOREIGN KEY FK_A98359D69D1C3019');
        $this->addSql('ALTER TABLE chat_subject DROP FOREIGN KEY FK_B7C0F21372F5A1AA');
        $this->addSql('ALTER TABLE frames_channel_participants DROP FOREIGN KEY FK_8F504A0BA76ED395');
        $this->addSql('ALTER TABLE frames_message_attachments DROP FOREIGN KEY FK_D340B496537A1329');
        $this->addSql('ALTER TABLE frames_messages DROP FOREIGN KEY FK_F6B8C09AF624B39D');
        $this->addSql('DROP TABLE abstract_chat_channel');
        $this->addSql('DROP TABLE abstract_chat_participant');
        $this->addSql('DROP TABLE chat_base_messages');
        $this->addSql('DROP TABLE chat_channel_role');
        $this->addSql('DROP TABLE chat_direct_messages');
        $this->addSql('DROP TABLE chat_message');
        $this->addSql('DROP TABLE chat_message_attachment');
        $this->addSql('DROP TABLE chat_message_view');
        $this->addSql('DROP TABLE chat_subject');
        $this->addSql('DROP TABLE frames_channel_participants');
        $this->addSql('DROP TABLE frames_channels');
        $this->addSql('DROP TABLE frames_message_attachments');
        $this->addSql('DROP TABLE frames_messages');
        $this->addSql('DROP TABLE frames_users');
    }
}
