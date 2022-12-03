<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221203171341 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tournament (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, city VARCHAR(255) NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, season VARCHAR(100) NOT NULL, standard_price1 INT DEFAULT NULL, standard_price2 INT DEFAULT NULL, standard_price3 INT DEFAULT NULL, elite_price1 INT DEFAULT NULL, elite_price2 INT DEFAULT NULL, elite_price3 INT DEFAULT NULL, registration_closing_date DATETIME DEFAULT NULL, random_draw DATETIME DEFAULT NULL, email_contact VARCHAR(255) DEFAULT NULL, tel_contact VARCHAR(30) DEFAULT NULL, registration_method VARCHAR(100) DEFAULT NULL, payment_method VARCHAR(100) DEFAULT NULL, comment LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE tournament');
    }
}
