<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260111192524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE book (id INT UNSIGNED AUTO_INCREMENT NOT NULL, category_id INT UNSIGNED DEFAULT NULL, barcode_id VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, subtitle VARCHAR(255) DEFAULT NULL, series VARCHAR(255) DEFAULT NULL, authors JSON NOT NULL, publisher VARCHAR(255) DEFAULT NULL, isbn VARCHAR(17) NOT NULL, year INT DEFAULT NULL, comment LONGTEXT DEFAULT NULL, cover_file_name VARCHAR(255) DEFAULT NULL, topic VARCHAR(255) DEFAULT NULL, shelfmark VARCHAR(255) DEFAULT NULL, is_borrowable TINYINT(1) NOT NULL, is_listed TINYINT(1) NOT NULL, receipt_date DATE NOT NULL, last_inventory_date DATE DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, uuid VARCHAR(36) NOT NULL, created_at DATETIME NOT NULL, created_by VARCHAR(255) NOT NULL, updated_at DATETIME DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_CBE5A33129439E58 (barcode_id), UNIQUE INDEX UNIQ_CBE5A331D17F50A6 (uuid), INDEX IDX_CBE5A33112469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE borrower (id INT UNSIGNED AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, barcode_id VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, grade VARCHAR(255) DEFAULT NULL, uuid VARCHAR(36) NOT NULL, UNIQUE INDEX UNIQ_DB904DB429439E58 (barcode_id), UNIQUE INDEX UNIQ_DB904DB4E7927C74 (email), UNIQUE INDEX UNIQ_DB904DB4D17F50A6 (uuid), UNIQUE INDEX UNIQ_DB904DB429439E588CDE5729 (barcode_id, type), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT UNSIGNED AUTO_INCREMENT NOT NULL, abbreviation VARCHAR(16) NOT NULL, name VARCHAR(255) NOT NULL, shelfmark_generator VARCHAR(255) NOT NULL, shelfmark_generator_parameter VARCHAR(255) DEFAULT NULL, uuid VARCHAR(36) NOT NULL, created_at DATETIME NOT NULL, created_by VARCHAR(255) NOT NULL, updated_at DATETIME DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_64C19C1BCF3411D (abbreviation), UNIQUE INDEX UNIQ_64C19C1D17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE checkout (id INT UNSIGNED AUTO_INCREMENT NOT NULL, book_id INT UNSIGNED DEFAULT NULL, borrower_id INT UNSIGNED DEFAULT NULL, start DATETIME NOT NULL, expected_end DATETIME NOT NULL, end DATETIME DEFAULT NULL, accepted_by VARCHAR(255) DEFAULT NULL, comment LONGTEXT DEFAULT NULL, uuid VARCHAR(36) NOT NULL, created_at DATETIME NOT NULL, created_by VARCHAR(255) NOT NULL, updated_at DATETIME DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_AF382D4ED17F50A6 (uuid), INDEX IDX_AF382D4E16A2B381 (book_id), INDEX IDX_AF382D4E11CE312B (borrower_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE id_entity (entity_id VARCHAR(255) NOT NULL, id VARCHAR(255) NOT NULL, expiry DATETIME NOT NULL, PRIMARY KEY(entity_id, id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE label_template (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, `rows` INT NOT NULL, `columns` INT NOT NULL, top_margin_mm DOUBLE PRECISION NOT NULL, bottom_margin_mm DOUBLE PRECISION NOT NULL, left_margin_mm DOUBLE PRECISION NOT NULL, right_margin_mm DOUBLE PRECISION NOT NULL, cell_width_mm DOUBLE PRECISION NOT NULL, cell_height_mm DOUBLE PRECISION NOT NULL, cell_padding_mm DOUBLE PRECISION NOT NULL, uuid VARCHAR(36) NOT NULL, UNIQUE INDEX UNIQ_D56E0646D17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `log` (id INT AUTO_INCREMENT NOT NULL, channel VARCHAR(255) NOT NULL, level INT NOT NULL, message LONGTEXT NOT NULL, time DATETIME NOT NULL, details JSON DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_processed_messages (id INT AUTO_INCREMENT NOT NULL, run_id INT NOT NULL, attempt SMALLINT NOT NULL, message_type VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, dispatched_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', received_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', finished_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', wait_time BIGINT NOT NULL, handle_time BIGINT NOT NULL, memory_usage BIGINT NOT NULL, transport VARCHAR(255) NOT NULL, tags VARCHAR(255) DEFAULT NULL, failure_type VARCHAR(255) DEFAULT NULL, failure_message LONGTEXT DEFAULT NULL, results JSON DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE setting (id INT UNSIGNED AUTO_INCREMENT NOT NULL, `key` VARCHAR(255) NOT NULL, `data` JSON DEFAULT NULL, UNIQUE INDEX UNIQ_9F74B8984E645A7E (`key`), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT UNSIGNED AUTO_INCREMENT NOT NULL, idp_identifier BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', username VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, roles JSON NOT NULL, uuid VARCHAR(36) NOT NULL, UNIQUE INDEX UNIQ_8D93D64966D2FA6C (idp_identifier), UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649D17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_borrower (user_id INT UNSIGNED NOT NULL, borrower_id INT UNSIGNED NOT NULL, INDEX IDX_3B1DA9B4A76ED395 (user_id), INDEX IDX_3B1DA9B411CE312B (borrower_id), PRIMARY KEY(user_id, borrower_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A33112469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE checkout ADD CONSTRAINT FK_AF382D4E16A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE checkout ADD CONSTRAINT FK_AF382D4E11CE312B FOREIGN KEY (borrower_id) REFERENCES borrower (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE user_borrower ADD CONSTRAINT FK_3B1DA9B4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_borrower ADD CONSTRAINT FK_3B1DA9B411CE312B FOREIGN KEY (borrower_id) REFERENCES borrower (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A33112469DE2');
        $this->addSql('ALTER TABLE checkout DROP FOREIGN KEY FK_AF382D4E16A2B381');
        $this->addSql('ALTER TABLE checkout DROP FOREIGN KEY FK_AF382D4E11CE312B');
        $this->addSql('ALTER TABLE user_borrower DROP FOREIGN KEY FK_3B1DA9B4A76ED395');
        $this->addSql('ALTER TABLE user_borrower DROP FOREIGN KEY FK_3B1DA9B411CE312B');
        $this->addSql('DROP TABLE book');
        $this->addSql('DROP TABLE borrower');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE checkout');
        $this->addSql('DROP TABLE id_entity');
        $this->addSql('DROP TABLE label_template');
        $this->addSql('DROP TABLE `log`');
        $this->addSql('DROP TABLE messenger_processed_messages');
        $this->addSql('DROP TABLE setting');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_borrower');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
