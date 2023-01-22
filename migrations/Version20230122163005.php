<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230122163005 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tournament_registration ADD user_last_name VARCHAR(100) DEFAULT NULL, ADD user_first_name VARCHAR(100) DEFAULT NULL, ADD user_email VARCHAR(255) DEFAULT NULL, ADD tournament_name VARCHAR(255) DEFAULT NULL, ADD tournament_city VARCHAR(255) DEFAULT NULL, ADD tournament_start_date DATETIME DEFAULT NULL, ADD tournament_end_date DATETIME DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL, CHANGE tournament_id tournament_id INT DEFAULT NULL, CHANGE participation_single participation_single TINYINT(1) NOT NULL, CHANGE participation_double participation_double TINYINT(1) NOT NULL, CHANGE participation_mixed participation_mixed TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tournament_registration DROP user_last_name, DROP user_first_name, DROP user_email, DROP tournament_name, DROP tournament_city, DROP tournament_start_date, DROP tournament_end_date, CHANGE user_id user_id INT NOT NULL, CHANGE tournament_id tournament_id INT NOT NULL, CHANGE participation_single participation_single TINYINT(1) DEFAULT NULL, CHANGE participation_double participation_double TINYINT(1) DEFAULT NULL, CHANGE participation_mixed participation_mixed TINYINT(1) DEFAULT NULL');
    }
}
