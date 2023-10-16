<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231015095607 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abstract_chat_channel CHANGE code code VARCHAR(64) DEFAULT NULL');
        $this->addSql('ALTER TABLE chat_channel CHANGE code code VARCHAR(64) DEFAULT NULL');
        $this->addSql('ALTER TABLE dmchat_channel CHANGE code code VARCHAR(64) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abstract_chat_channel CHANGE code code VARCHAR(64) NOT NULL');
        $this->addSql('ALTER TABLE chat_channel CHANGE code code VARCHAR(64) NOT NULL');
        $this->addSql('ALTER TABLE dmchat_channel CHANGE code code VARCHAR(64) NOT NULL');
    }
}
