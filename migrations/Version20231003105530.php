<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231003105530 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', coordinate_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', first_name VARCHAR(64) DEFAULT NULL, last_name VARCHAR(64) DEFAULT NULL, phone_number VARCHAR(32) DEFAULT NULL, email_address VARCHAR(64) DEFAULT NULL, company VARCHAR(64) DEFAULT NULL, country_code VARCHAR(3) NOT NULL, province_code VARCHAR(16) DEFAULT NULL, province_name VARCHAR(64) DEFAULT NULL, city VARCHAR(64) DEFAULT NULL, street VARCHAR(128) DEFAULT NULL, postcode VARCHAR(8) DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', google_id VARCHAR(255) DEFAULT NULL, formatted VARCHAR(255) DEFAULT NULL, arrive_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', dtype VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_D4E6F8198BBE953 (coordinate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE address_coordinates (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, altitude DOUBLE PRECISION DEFAULT NULL, accuracy DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE assessment_parameter (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', code VARCHAR(32) NOT NULL, title VARCHAR(16) NOT NULL, subtitle VARCHAR(32) NOT NULL, description LONGTEXT DEFAULT NULL, icon VARCHAR(32) DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_F8E662FC77153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat_base_messages (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', title VARCHAR(100) DEFAULT NULL, body VARCHAR(4000) NOT NULL, sent_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', dtype VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat_channel (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', code VARCHAR(64) NOT NULL, reference VARCHAR(255) NOT NULL, subject VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', participant_count INT DEFAULT 0 NOT NULL, title VARCHAR(64) DEFAULT NULL, subtitle VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat_channel_role (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', channel_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', code VARCHAR(64) NOT NULL, title VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', max_num_of_participant INT NOT NULL, INDEX IDX_35D6409572F5A1AA (channel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat_direct_messages (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', conversation_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', INDEX IDX_110C810D9AC0396 (conversation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat_message (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', channel_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', subject_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', participant_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', INDEX IDX_FAB3FC1672F5A1AA (channel_id), INDEX IDX_FAB3FC1623EDC87 (subject_id), INDEX IDX_FAB3FC169D1C3019 (participant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat_message_attachment (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', message_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', uri VARCHAR(255) NOT NULL, type VARCHAR(255) DEFAULT NULL, size INT DEFAULT NULL, INDEX IDX_4965BF86537A1329 (message_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat_message_view (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', message_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', participant_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', viewed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_A98359D6537A1329 (message_id), INDEX IDX_A98359D69D1C3019 (participant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat_participant (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', channel_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', role_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', code VARCHAR(64) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', name VARCHAR(128) DEFAULT NULL, dtype VARCHAR(255) NOT NULL, business_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', user_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', INDEX IDX_E8ED9C8972F5A1AA (channel_id), INDEX IDX_E8ED9C89D60322AC (role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat_subject (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', channel_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', title VARCHAR(64) DEFAULT NULL, subtitle VARCHAR(255) DEFAULT NULL, INDEX IDX_B7C0F21372F5A1AA (channel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conversation (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', sender_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', receiver_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_8A8E26E9F624B39D (sender_id), INDEX IDX_8A8E26E9CD53EDB6 (receiver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE driver (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', user_account_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', verified TINYINT(1) DEFAULT 1 NOT NULL, status VARCHAR(32) NOT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_11667CD93C0C9956 (user_account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE driver_license (id INT AUTO_INCREMENT NOT NULL, license_number VARCHAR(32) NOT NULL, issuance_state VARCHAR(32) DEFAULT NULL, class VARCHAR(32) DEFAULT NULL, expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE frames_channel_participants (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_8F504A0BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE frames_channels (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE frames_message_attachments (id INT AUTO_INCREMENT NOT NULL, message_id INT NOT NULL, type VARCHAR(32) NOT NULL, uri VARCHAR(255) NOT NULL, caption VARCHAR(128) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_D340B496537A1329 (message_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE frames_messages (id INT AUTO_INCREMENT NOT NULL, sender_id INT NOT NULL, title VARCHAR(64) DEFAULT NULL, body LONGTEXT DEFAULT NULL, INDEX IDX_F6B8C09AF624B39D (sender_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE frames_users (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, short_name VARCHAR(16) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participant (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', label VARCHAR(64) NOT NULL, reference VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', price_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', dimension_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', category_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', title VARCHAR(64) NOT NULL, description VARCHAR(255) DEFAULT NULL, weight INT DEFAULT 0 NOT NULL, dtype VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_D34A04ADD614C7E7 (price_id), UNIQUE INDEX UNIQ_D34A04AD277428AD (dimension_id), INDEX IDX_D34A04AD12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_category (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', code VARCHAR(32) NOT NULL, name VARCHAR(64) NOT NULL, short_name VARCHAR(16) DEFAULT NULL, icon_image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_dimension (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', length INT NOT NULL, width INT NOT NULL, height INT NOT NULL, unit VARCHAR(16) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_price (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', currency VARCHAR(3) NOT NULL, amount INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE review (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', rating SMALLINT NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE route (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', code VARCHAR(64) NOT NULL, name VARCHAR(128) DEFAULT NULL, start_point POINT DEFAULT NULL COMMENT \'(DC2Type:Point)\', end_point POINT DEFAULT NULL COMMENT \'(DC2Type:Point)\', start_place_id VARCHAR(255) DEFAULT NULL, end_place_id VARCHAR(255) DEFAULT NULL, polyline LINESTRING NOT NULL COMMENT \'(DC2Type:LineString)\', distance INT DEFAULT NULL, duration INT DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE route_segment (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shipment (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', owner_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', billing_address_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', origin_address_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', destination_address_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', budget_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', route_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', type VARCHAR(64) NOT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status VARCHAR(64) DEFAULT \'PENDING\' NOT NULL, pickup_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivery_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', code VARCHAR(32) NOT NULL, total_weight INT DEFAULT 0 NOT NULL, INDEX IDX_2CB20DC7E3C61F9 (owner_id), INDEX IDX_2CB20DC79D0C0E4 (billing_address_id), INDEX IDX_2CB20DC4C6CF538 (origin_address_id), INDEX IDX_2CB20DCA88E34C7 (destination_address_id), UNIQUE INDEX UNIQ_2CB20DC36ABA6B8 (budget_id), INDEX IDX_2CB20DC34ECB4E6 (route_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shipment_budget (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', currency VARCHAR(3) NOT NULL, dtype VARCHAR(255) NOT NULL, price INT DEFAULT NULL, min_price INT DEFAULT NULL, max_price INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shipment_document (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', shipment_order_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', meta LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', type VARCHAR(32) NOT NULL, INDEX IDX_7E3E75172BC89259 (shipment_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shipment_document_attachment (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', document_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', type VARCHAR(32) NOT NULL, src VARCHAR(225) NOT NULL, caption VARCHAR(255) DEFAULT NULL, meta LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', INDEX IDX_D2902EB5C33F7837 (document_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shipment_driver_bid (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', shipment_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', driver_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', vehicle_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', price_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', title VARCHAR(64) DEFAULT NULL, description VARCHAR(1000) DEFAULT NULL, status VARCHAR(64) NOT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', pickup_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivery_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_9DD32C17BE036FC (shipment_id), INDEX IDX_9DD32C1C3423909 (driver_id), INDEX IDX_9DD32C1545317D1 (vehicle_id), UNIQUE INDEX UNIQ_9DD32C1D614C7E7 (price_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shipment_driver_bid_price (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', currency VARCHAR(3) NOT NULL, amount INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shipment_execution (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', driver_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', vehicle_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', starting_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', label VARCHAR(64) DEFAULT NULL, description LONGTEXT DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', code VARCHAR(32) NOT NULL, status VARCHAR(32) NOT NULL, INDEX IDX_38B3D10BC3423909 (driver_id), INDEX IDX_38B3D10B545317D1 (vehicle_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shipment_item (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', shipment_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', product_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', quantity INT NOT NULL, description VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_1C573407BE036FC (shipment_id), INDEX IDX_1C573404584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shipment_order (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', driver_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', shipper_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', shipment_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', bid_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', vehicle_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', execution_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', pickup_confirmation_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', proof_of_delivery_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', review_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', status VARCHAR(32) NOT NULL, pickup_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivery_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expected_pickup_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', expected_delivery_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', activity_count INT NOT NULL, code VARCHAR(32) NOT NULL, currency VARCHAR(3) NOT NULL, subtotal INT NOT NULL, charge_total INT NOT NULL, total INT NOT NULL, INDEX IDX_79E4313DC3423909 (driver_id), INDEX IDX_79E4313D38459F23 (shipper_id), UNIQUE INDEX UNIQ_79E4313D7BE036FC (shipment_id), UNIQUE INDEX UNIQ_79E4313D4D9866B8 (bid_id), INDEX IDX_79E4313D545317D1 (vehicle_id), INDEX IDX_79E4313D57125544 (execution_id), UNIQUE INDEX UNIQ_79E4313D68326688 (pickup_confirmation_id), UNIQUE INDEX UNIQ_79E4313D9B5B8FB1 (proof_of_delivery_id), UNIQUE INDEX UNIQ_79E4313D3E2E969B (review_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shipment_order_activity (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', shipment_order_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', reference VARCHAR(32) NOT NULL, type VARCHAR(16) NOT NULL, label VARCHAR(64) NOT NULL, description LONGTEXT DEFAULT NULL, occured_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_CF6FE6512BC89259 (shipment_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shipment_order_charge (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', shipment_order_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', label VARCHAR(32) NOT NULL, amount INT NOT NULL, type VARCHAR(16) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_61A9DD242BC89259 (shipment_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shipment_proof_document (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unit_review (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', review_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', paramter_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', rating SMALLINT NOT NULL, description VARCHAR(255) DEFAULT NULL, type VARCHAR(32) NOT NULL, INDEX IDX_EE1A6A303E2E969B (review_id), INDEX IDX_EE1A6A301AA229DD (paramter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', address_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, first_name VARCHAR(64) NOT NULL, last_name VARCHAR(64) NOT NULL, phone VARCHAR(64) DEFAULT NULL, is_verified TINYINT(1) NOT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649F5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_address (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', owner_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', INDEX IDX_5543718B7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_product (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', owner_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', INDEX IDX_8B471AA77E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vehicle (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', type_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', driver_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', dimension_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:ulid)\', status VARCHAR(32) NOT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', vin VARCHAR(32) NOT NULL, license_plate_number VARCHAR(16) NOT NULL, max_weight_capacity INT DEFAULT NULL, note VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_1B80E486C54C8C93 (type_id), INDEX IDX_1B80E486C3423909 (driver_id), UNIQUE INDEX UNIQ_1B80E486277428AD (dimension_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vehicle_dimension (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', length INT NOT NULL, width INT NOT NULL, height INT NOT NULL, unit VARCHAR(32) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vehicle_type (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', code VARCHAR(32) NOT NULL, short_name VARCHAR(12) NOT NULL, name VARCHAR(64) NOT NULL, icon_image VARCHAR(255) DEFAULT NULL, primary_image VARCHAR(255) DEFAULT NULL, cover_image VARCHAR(255) DEFAULT NULL, client_note VARCHAR(255) DEFAULT NULL, driver_note VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, note VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F8198BBE953 FOREIGN KEY (coordinate_id) REFERENCES address_coordinates (id)');
        $this->addSql('ALTER TABLE chat_channel_role ADD CONSTRAINT FK_35D6409572F5A1AA FOREIGN KEY (channel_id) REFERENCES chat_channel (id)');
        $this->addSql('ALTER TABLE chat_direct_messages ADD CONSTRAINT FK_110C810D9AC0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id)');
        $this->addSql('ALTER TABLE chat_direct_messages ADD CONSTRAINT FK_110C810DBF396750 FOREIGN KEY (id) REFERENCES chat_base_messages (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chat_message ADD CONSTRAINT FK_FAB3FC1672F5A1AA FOREIGN KEY (channel_id) REFERENCES chat_channel (id)');
        $this->addSql('ALTER TABLE chat_message ADD CONSTRAINT FK_FAB3FC1623EDC87 FOREIGN KEY (subject_id) REFERENCES chat_subject (id)');
        $this->addSql('ALTER TABLE chat_message ADD CONSTRAINT FK_FAB3FC169D1C3019 FOREIGN KEY (participant_id) REFERENCES chat_participant (id)');
        $this->addSql('ALTER TABLE chat_message ADD CONSTRAINT FK_FAB3FC16BF396750 FOREIGN KEY (id) REFERENCES chat_base_messages (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chat_message_attachment ADD CONSTRAINT FK_4965BF86537A1329 FOREIGN KEY (message_id) REFERENCES chat_base_messages (id)');
        $this->addSql('ALTER TABLE chat_message_view ADD CONSTRAINT FK_A98359D6537A1329 FOREIGN KEY (message_id) REFERENCES chat_message (id)');
        $this->addSql('ALTER TABLE chat_message_view ADD CONSTRAINT FK_A98359D69D1C3019 FOREIGN KEY (participant_id) REFERENCES chat_participant (id)');
        $this->addSql('ALTER TABLE chat_participant ADD CONSTRAINT FK_E8ED9C8972F5A1AA FOREIGN KEY (channel_id) REFERENCES chat_channel (id)');
        $this->addSql('ALTER TABLE chat_participant ADD CONSTRAINT FK_E8ED9C89D60322AC FOREIGN KEY (role_id) REFERENCES chat_channel_role (id)');
        $this->addSql('ALTER TABLE chat_subject ADD CONSTRAINT FK_B7C0F21372F5A1AA FOREIGN KEY (channel_id) REFERENCES chat_channel (id)');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E9F624B39D FOREIGN KEY (sender_id) REFERENCES participant (id)');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E9CD53EDB6 FOREIGN KEY (receiver_id) REFERENCES participant (id)');
        $this->addSql('ALTER TABLE driver ADD CONSTRAINT FK_11667CD93C0C9956 FOREIGN KEY (user_account_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE frames_channel_participants ADD CONSTRAINT FK_8F504A0BA76ED395 FOREIGN KEY (user_id) REFERENCES frames_users (id)');
        $this->addSql('ALTER TABLE frames_message_attachments ADD CONSTRAINT FK_D340B496537A1329 FOREIGN KEY (message_id) REFERENCES frames_messages (id)');
        $this->addSql('ALTER TABLE frames_messages ADD CONSTRAINT FK_F6B8C09AF624B39D FOREIGN KEY (sender_id) REFERENCES frames_channel_participants (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADD614C7E7 FOREIGN KEY (price_id) REFERENCES product_price (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD277428AD FOREIGN KEY (dimension_id) REFERENCES product_dimension (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES product_category (id)');
        $this->addSql('ALTER TABLE shipment ADD CONSTRAINT FK_2CB20DC7E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE shipment ADD CONSTRAINT FK_2CB20DC79D0C0E4 FOREIGN KEY (billing_address_id) REFERENCES user_address (id)');
        $this->addSql('ALTER TABLE shipment ADD CONSTRAINT FK_2CB20DC4C6CF538 FOREIGN KEY (origin_address_id) REFERENCES user_address (id)');
        $this->addSql('ALTER TABLE shipment ADD CONSTRAINT FK_2CB20DCA88E34C7 FOREIGN KEY (destination_address_id) REFERENCES user_address (id)');
        $this->addSql('ALTER TABLE shipment ADD CONSTRAINT FK_2CB20DC36ABA6B8 FOREIGN KEY (budget_id) REFERENCES shipment_budget (id)');
        $this->addSql('ALTER TABLE shipment ADD CONSTRAINT FK_2CB20DC34ECB4E6 FOREIGN KEY (route_id) REFERENCES route (id)');
        $this->addSql('ALTER TABLE shipment_document ADD CONSTRAINT FK_7E3E75172BC89259 FOREIGN KEY (shipment_order_id) REFERENCES shipment_order (id)');
        $this->addSql('ALTER TABLE shipment_document_attachment ADD CONSTRAINT FK_D2902EB5C33F7837 FOREIGN KEY (document_id) REFERENCES shipment_document (id)');
        $this->addSql('ALTER TABLE shipment_driver_bid ADD CONSTRAINT FK_9DD32C17BE036FC FOREIGN KEY (shipment_id) REFERENCES shipment (id)');
        $this->addSql('ALTER TABLE shipment_driver_bid ADD CONSTRAINT FK_9DD32C1C3423909 FOREIGN KEY (driver_id) REFERENCES driver (id)');
        $this->addSql('ALTER TABLE shipment_driver_bid ADD CONSTRAINT FK_9DD32C1545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('ALTER TABLE shipment_driver_bid ADD CONSTRAINT FK_9DD32C1D614C7E7 FOREIGN KEY (price_id) REFERENCES shipment_driver_bid_price (id)');
        $this->addSql('ALTER TABLE shipment_execution ADD CONSTRAINT FK_38B3D10BC3423909 FOREIGN KEY (driver_id) REFERENCES driver (id)');
        $this->addSql('ALTER TABLE shipment_execution ADD CONSTRAINT FK_38B3D10B545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('ALTER TABLE shipment_item ADD CONSTRAINT FK_1C573407BE036FC FOREIGN KEY (shipment_id) REFERENCES shipment (id)');
        $this->addSql('ALTER TABLE shipment_item ADD CONSTRAINT FK_1C573404584665A FOREIGN KEY (product_id) REFERENCES user_product (id)');
        $this->addSql('ALTER TABLE shipment_order ADD CONSTRAINT FK_79E4313DC3423909 FOREIGN KEY (driver_id) REFERENCES driver (id)');
        $this->addSql('ALTER TABLE shipment_order ADD CONSTRAINT FK_79E4313D38459F23 FOREIGN KEY (shipper_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE shipment_order ADD CONSTRAINT FK_79E4313D7BE036FC FOREIGN KEY (shipment_id) REFERENCES shipment (id)');
        $this->addSql('ALTER TABLE shipment_order ADD CONSTRAINT FK_79E4313D4D9866B8 FOREIGN KEY (bid_id) REFERENCES shipment_driver_bid (id)');
        $this->addSql('ALTER TABLE shipment_order ADD CONSTRAINT FK_79E4313D545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('ALTER TABLE shipment_order ADD CONSTRAINT FK_79E4313D57125544 FOREIGN KEY (execution_id) REFERENCES shipment_execution (id)');
        $this->addSql('ALTER TABLE shipment_order ADD CONSTRAINT FK_79E4313D68326688 FOREIGN KEY (pickup_confirmation_id) REFERENCES shipment_document (id)');
        $this->addSql('ALTER TABLE shipment_order ADD CONSTRAINT FK_79E4313D9B5B8FB1 FOREIGN KEY (proof_of_delivery_id) REFERENCES shipment_document (id)');
        $this->addSql('ALTER TABLE shipment_order ADD CONSTRAINT FK_79E4313D3E2E969B FOREIGN KEY (review_id) REFERENCES review (id)');
        $this->addSql('ALTER TABLE shipment_order_activity ADD CONSTRAINT FK_CF6FE6512BC89259 FOREIGN KEY (shipment_order_id) REFERENCES shipment_order (id)');
        $this->addSql('ALTER TABLE shipment_order_charge ADD CONSTRAINT FK_61A9DD242BC89259 FOREIGN KEY (shipment_order_id) REFERENCES shipment_order (id)');
        $this->addSql('ALTER TABLE unit_review ADD CONSTRAINT FK_EE1A6A303E2E969B FOREIGN KEY (review_id) REFERENCES review (id)');
        $this->addSql('ALTER TABLE unit_review ADD CONSTRAINT FK_EE1A6A301AA229DD FOREIGN KEY (paramter_id) REFERENCES assessment_parameter (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE user_address ADD CONSTRAINT FK_5543718B7E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user_address ADD CONSTRAINT FK_5543718BBF396750 FOREIGN KEY (id) REFERENCES address (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_product ADD CONSTRAINT FK_8B471AA77E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user_product ADD CONSTRAINT FK_8B471AA7BF396750 FOREIGN KEY (id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E486C54C8C93 FOREIGN KEY (type_id) REFERENCES vehicle_type (id)');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E486C3423909 FOREIGN KEY (driver_id) REFERENCES driver (id)');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E486277428AD FOREIGN KEY (dimension_id) REFERENCES vehicle_dimension (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F8198BBE953');
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
        $this->addSql('ALTER TABLE chat_participant DROP FOREIGN KEY FK_E8ED9C8972F5A1AA');
        $this->addSql('ALTER TABLE chat_participant DROP FOREIGN KEY FK_E8ED9C89D60322AC');
        $this->addSql('ALTER TABLE chat_subject DROP FOREIGN KEY FK_B7C0F21372F5A1AA');
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E9F624B39D');
        $this->addSql('ALTER TABLE conversation DROP FOREIGN KEY FK_8A8E26E9CD53EDB6');
        $this->addSql('ALTER TABLE driver DROP FOREIGN KEY FK_11667CD93C0C9956');
        $this->addSql('ALTER TABLE frames_channel_participants DROP FOREIGN KEY FK_8F504A0BA76ED395');
        $this->addSql('ALTER TABLE frames_message_attachments DROP FOREIGN KEY FK_D340B496537A1329');
        $this->addSql('ALTER TABLE frames_messages DROP FOREIGN KEY FK_F6B8C09AF624B39D');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADD614C7E7');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD277428AD');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE shipment DROP FOREIGN KEY FK_2CB20DC7E3C61F9');
        $this->addSql('ALTER TABLE shipment DROP FOREIGN KEY FK_2CB20DC79D0C0E4');
        $this->addSql('ALTER TABLE shipment DROP FOREIGN KEY FK_2CB20DC4C6CF538');
        $this->addSql('ALTER TABLE shipment DROP FOREIGN KEY FK_2CB20DCA88E34C7');
        $this->addSql('ALTER TABLE shipment DROP FOREIGN KEY FK_2CB20DC36ABA6B8');
        $this->addSql('ALTER TABLE shipment DROP FOREIGN KEY FK_2CB20DC34ECB4E6');
        $this->addSql('ALTER TABLE shipment_document DROP FOREIGN KEY FK_7E3E75172BC89259');
        $this->addSql('ALTER TABLE shipment_document_attachment DROP FOREIGN KEY FK_D2902EB5C33F7837');
        $this->addSql('ALTER TABLE shipment_driver_bid DROP FOREIGN KEY FK_9DD32C17BE036FC');
        $this->addSql('ALTER TABLE shipment_driver_bid DROP FOREIGN KEY FK_9DD32C1C3423909');
        $this->addSql('ALTER TABLE shipment_driver_bid DROP FOREIGN KEY FK_9DD32C1545317D1');
        $this->addSql('ALTER TABLE shipment_driver_bid DROP FOREIGN KEY FK_9DD32C1D614C7E7');
        $this->addSql('ALTER TABLE shipment_execution DROP FOREIGN KEY FK_38B3D10BC3423909');
        $this->addSql('ALTER TABLE shipment_execution DROP FOREIGN KEY FK_38B3D10B545317D1');
        $this->addSql('ALTER TABLE shipment_item DROP FOREIGN KEY FK_1C573407BE036FC');
        $this->addSql('ALTER TABLE shipment_item DROP FOREIGN KEY FK_1C573404584665A');
        $this->addSql('ALTER TABLE shipment_order DROP FOREIGN KEY FK_79E4313DC3423909');
        $this->addSql('ALTER TABLE shipment_order DROP FOREIGN KEY FK_79E4313D38459F23');
        $this->addSql('ALTER TABLE shipment_order DROP FOREIGN KEY FK_79E4313D7BE036FC');
        $this->addSql('ALTER TABLE shipment_order DROP FOREIGN KEY FK_79E4313D4D9866B8');
        $this->addSql('ALTER TABLE shipment_order DROP FOREIGN KEY FK_79E4313D545317D1');
        $this->addSql('ALTER TABLE shipment_order DROP FOREIGN KEY FK_79E4313D57125544');
        $this->addSql('ALTER TABLE shipment_order DROP FOREIGN KEY FK_79E4313D68326688');
        $this->addSql('ALTER TABLE shipment_order DROP FOREIGN KEY FK_79E4313D9B5B8FB1');
        $this->addSql('ALTER TABLE shipment_order DROP FOREIGN KEY FK_79E4313D3E2E969B');
        $this->addSql('ALTER TABLE shipment_order_activity DROP FOREIGN KEY FK_CF6FE6512BC89259');
        $this->addSql('ALTER TABLE shipment_order_charge DROP FOREIGN KEY FK_61A9DD242BC89259');
        $this->addSql('ALTER TABLE unit_review DROP FOREIGN KEY FK_EE1A6A303E2E969B');
        $this->addSql('ALTER TABLE unit_review DROP FOREIGN KEY FK_EE1A6A301AA229DD');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649F5B7AF75');
        $this->addSql('ALTER TABLE user_address DROP FOREIGN KEY FK_5543718B7E3C61F9');
        $this->addSql('ALTER TABLE user_address DROP FOREIGN KEY FK_5543718BBF396750');
        $this->addSql('ALTER TABLE user_product DROP FOREIGN KEY FK_8B471AA77E3C61F9');
        $this->addSql('ALTER TABLE user_product DROP FOREIGN KEY FK_8B471AA7BF396750');
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E486C54C8C93');
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E486C3423909');
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E486277428AD');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE address_coordinates');
        $this->addSql('DROP TABLE assessment_parameter');
        $this->addSql('DROP TABLE chat_base_messages');
        $this->addSql('DROP TABLE chat_channel');
        $this->addSql('DROP TABLE chat_channel_role');
        $this->addSql('DROP TABLE chat_direct_messages');
        $this->addSql('DROP TABLE chat_message');
        $this->addSql('DROP TABLE chat_message_attachment');
        $this->addSql('DROP TABLE chat_message_view');
        $this->addSql('DROP TABLE chat_participant');
        $this->addSql('DROP TABLE chat_subject');
        $this->addSql('DROP TABLE conversation');
        $this->addSql('DROP TABLE driver');
        $this->addSql('DROP TABLE driver_license');
        $this->addSql('DROP TABLE frames_channel_participants');
        $this->addSql('DROP TABLE frames_channels');
        $this->addSql('DROP TABLE frames_message_attachments');
        $this->addSql('DROP TABLE frames_messages');
        $this->addSql('DROP TABLE frames_users');
        $this->addSql('DROP TABLE participant');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_category');
        $this->addSql('DROP TABLE product_dimension');
        $this->addSql('DROP TABLE product_price');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE route');
        $this->addSql('DROP TABLE route_segment');
        $this->addSql('DROP TABLE shipment');
        $this->addSql('DROP TABLE shipment_budget');
        $this->addSql('DROP TABLE shipment_document');
        $this->addSql('DROP TABLE shipment_document_attachment');
        $this->addSql('DROP TABLE shipment_driver_bid');
        $this->addSql('DROP TABLE shipment_driver_bid_price');
        $this->addSql('DROP TABLE shipment_execution');
        $this->addSql('DROP TABLE shipment_item');
        $this->addSql('DROP TABLE shipment_order');
        $this->addSql('DROP TABLE shipment_order_activity');
        $this->addSql('DROP TABLE shipment_order_charge');
        $this->addSql('DROP TABLE shipment_proof_document');
        $this->addSql('DROP TABLE unit_review');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE user_address');
        $this->addSql('DROP TABLE user_product');
        $this->addSql('DROP TABLE vehicle');
        $this->addSql('DROP TABLE vehicle_dimension');
        $this->addSql('DROP TABLE vehicle_type');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
