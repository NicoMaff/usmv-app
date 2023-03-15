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
        $this->addSql('ALTER TABLE tournament_registration CHANGE has_participated has_participated TINYINT(1) DEFAULT NULL, CHANGE participation_single participation_single TINYINT(1) DEFAULT NULL, CHANGE participation_double participation_double TINYINT(1) DEFAULT NULL, CHANGE participation_mixed participation_mixed TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tournament_registration CHANGE has_participated has_participated TINYINT(1) NOT NULL, CHANGE participation_single participation_single TINYINT(1) NOT NULL, CHANGE participation_double participation_double TINYINT(1) NOT NULL, CHANGE participation_mixed participation_mixed TINYINT(1) NOT NULL');
    }
}
