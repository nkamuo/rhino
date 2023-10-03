<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231003045329 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE assessment_parameter (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', code VARCHAR(32) NOT NULL, title VARCHAR(16) NOT NULL, subtitle VARCHAR(32) NOT NULL, description LONGTEXT DEFAULT NULL, icon VARCHAR(32) DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_F8E662FC77153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE review CHANGE id id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\'');
        $this->addSql('ALTER TABLE shipment_order CHANGE review_id review_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\'');
        $this->addSql('ALTER TABLE unit_review ADD paramter_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', CHANGE id id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', CHANGE review_id review_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\'');
        $this->addSql('ALTER TABLE unit_review ADD CONSTRAINT FK_EE1A6A301AA229DD FOREIGN KEY (paramter_id) REFERENCES assessment_parameter (id)');
        $this->addSql('CREATE INDEX IDX_EE1A6A301AA229DD ON unit_review (paramter_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE unit_review DROP FOREIGN KEY FK_EE1A6A301AA229DD');
        $this->addSql('DROP TABLE assessment_parameter');
        $this->addSql('ALTER TABLE review CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE shipment_order CHANGE review_id review_id INT DEFAULT NULL');
        $this->addSql('DROP INDEX IDX_EE1A6A301AA229DD ON unit_review');
        $this->addSql('ALTER TABLE unit_review DROP paramter_id, CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE review_id review_id INT NOT NULL');
    }
}
