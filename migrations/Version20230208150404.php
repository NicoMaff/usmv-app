<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230208150404 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE blog (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tournament_registration CHANGE has_participated has_participated TINYINT(1) DEFAULT NULL, CHANGE participation_single participation_single TINYINT(1) DEFAULT NULL, CHANGE participation_double participation_double TINYINT(1) DEFAULT NULL, CHANGE participation_mixed participation_mixed TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE blog');
        $this->addSql('ALTER TABLE tournament_registration CHANGE has_participated has_participated TINYINT(1) NOT NULL, CHANGE participation_single participation_single TINYINT(1) NOT NULL, CHANGE participation_double participation_double TINYINT(1) NOT NULL, CHANGE participation_mixed participation_mixed TINYINT(1) NOT NULL');
    }
}
