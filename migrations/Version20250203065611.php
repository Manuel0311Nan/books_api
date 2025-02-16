<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250203065611 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, page_id INT DEFAULT NULL, book_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, power VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_1F1B251EC4663E4 (page_id), UNIQUE INDEX UNIQ_1F1B251E16A2B381 (book_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251EC4663E4 FOREIGN KEY (page_id) REFERENCES page (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E16A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE book ADD image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE page ADD image VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251EC4663E4');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251E16A2B381');
        $this->addSql('DROP TABLE item');
        $this->addSql('ALTER TABLE book DROP image');
        $this->addSql('ALTER TABLE page DROP image');
    }
}
