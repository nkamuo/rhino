<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231017120402 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE driver_subscription (id INT AUTO_INCREMENT NOT NULL, stripe_subscription_id VARCHAR(128) DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_method (id INT AUTO_INCREMENT NOT NULL, owner_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', stripe_id VARCHAR(128) DEFAULT NULL, INDEX IDX_7B61A1F67E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE payment_method ADD CONSTRAINT FK_7B61A1F67E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE driver ADD subscription_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE driver ADD CONSTRAINT FK_11667CD99A1887DC FOREIGN KEY (subscription_id) REFERENCES driver_subscription (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_11667CD99A1887DC ON driver (subscription_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE driver DROP FOREIGN KEY FK_11667CD99A1887DC');
        $this->addSql('ALTER TABLE payment_method DROP FOREIGN KEY FK_7B61A1F67E3C61F9');
        $this->addSql('DROP TABLE driver_subscription');
        $this->addSql('DROP TABLE payment_method');
        $this->addSql('DROP INDEX UNIQ_11667CD99A1887DC ON driver');
        $this->addSql('ALTER TABLE driver DROP subscription_id');
    }
}
