<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221215175322 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tournament_registration (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, tournament_id INT NOT NULL, request_state VARCHAR(100) NOT NULL, has_participated TINYINT(1) NOT NULL, participation_single TINYINT(1) DEFAULT NULL, participation_double TINYINT(1) DEFAULT NULL, participation_mixed TINYINT(1) DEFAULT NULL, single_stage_reached VARCHAR(20) DEFAULT NULL, double_stage_reached VARCHAR(20) DEFAULT NULL, mixed_stage_reached VARCHAR(20) DEFAULT NULL, double_partner_name VARCHAR(255) DEFAULT NULL, double_partner_club VARCHAR(255) DEFAULT NULL, mixed_partner_name VARCHAR(255) DEFAULT NULL, mixed_partner_club VARCHAR(255) DEFAULT NULL, comment LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL, INDEX IDX_F42ADBF1A76ED395 (user_id), INDEX IDX_F42ADBF133D1A3E7 (tournament_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tournament_registration ADD CONSTRAINT FK_F42ADBF1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tournament_registration ADD CONSTRAINT FK_F42ADBF133D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id)');
        $this->addSql('ALTER TABLE article ADD main_image_name VARCHAR(255) DEFAULT NULL, ADD main_image_url VARCHAR(255) DEFAULT NULL, ADD first_additional_image_name VARCHAR(255) DEFAULT NULL, ADD first_additional_image_url VARCHAR(255) DEFAULT NULL, ADD second_additional_image_name VARCHAR(255) DEFAULT NULL, ADD second_additional_image_url VARCHAR(255) DEFAULT NULL, ADD third_additional_image_name VARCHAR(255) DEFAULT NULL, ADD third_additional_image_url VARCHAR(255) DEFAULT NULL, ADD visible TINYINT(1) NOT NULL, DROP url_image_main, DROP url_image_additional_1, DROP url_image_additional_2, DROP url_image_additional_3');
        $this->addSql('ALTER TABLE event ADD image_url VARCHAR(255) DEFAULT NULL, CHANGE url_image image_name VARCHAR(255) DEFAULT NULL, CHANGE is_visible visible TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE tournament ADD is_team_competition TINYINT(1) NOT NULL, CHANGE end_date end_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD avatar_file_name VARCHAR(255) DEFAULT NULL, ADD avatar_file_url VARCHAR(255) DEFAULT NULL, DROP avatar_url, DROP avatar_name');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tournament_registration DROP FOREIGN KEY FK_F42ADBF1A76ED395');
        $this->addSql('ALTER TABLE tournament_registration DROP FOREIGN KEY FK_F42ADBF133D1A3E7');
        $this->addSql('DROP TABLE tournament_registration');
        $this->addSql('ALTER TABLE article ADD url_image_main VARCHAR(255) DEFAULT NULL, ADD url_image_additional_1 VARCHAR(255) DEFAULT NULL, ADD url_image_additional_2 VARCHAR(255) DEFAULT NULL, ADD url_image_additional_3 VARCHAR(255) DEFAULT NULL, DROP main_image_name, DROP main_image_url, DROP first_additional_image_name, DROP first_additional_image_url, DROP second_additional_image_name, DROP second_additional_image_url, DROP third_additional_image_name, DROP third_additional_image_url, DROP visible');
        $this->addSql('ALTER TABLE event ADD url_image VARCHAR(255) DEFAULT NULL, DROP image_name, DROP image_url, CHANGE visible is_visible TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE tournament DROP is_team_competition, CHANGE end_date end_date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user ADD avatar_url VARCHAR(255) DEFAULT NULL, ADD avatar_name VARCHAR(255) DEFAULT NULL, DROP avatar_file_name, DROP avatar_file_url');
    }
}
