<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221231140948 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ffbad_stat (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, extraction_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', rankings_date VARCHAR(50) DEFAULT NULL, week_number VARCHAR(10) DEFAULT NULL, year VARCHAR(10) DEFAULT NULL, season VARCHAR(15) DEFAULT NULL, api_id VARCHAR(15) DEFAULT NULL, license VARCHAR(10) DEFAULT NULL, last_name VARCHAR(50) DEFAULT NULL, birth_last_name VARCHAR(50) DEFAULT NULL, first_name VARCHAR(50) DEFAULT NULL, birth_date VARCHAR(15) DEFAULT NULL, nationality VARCHAR(20) DEFAULT NULL, country VARCHAR(50) DEFAULT NULL, country_iso VARCHAR(6) DEFAULT NULL, category_global VARCHAR(15) DEFAULT NULL, category_short VARCHAR(10) DEFAULT NULL, category_long VARCHAR(20) DEFAULT NULL, club_reference VARCHAR(50) DEFAULT NULL, club_acronym VARCHAR(10) DEFAULT NULL, club_id VARCHAR(10) DEFAULT NULL, club_name VARCHAR(255) DEFAULT NULL, club_department VARCHAR(5) DEFAULT NULL, is_player_transferred TINYINT(1) DEFAULT NULL, is_data_player_public TINYINT(1) DEFAULT NULL, is_player_active TINYINT(1) DEFAULT NULL, feather VARCHAR(20) DEFAULT NULL, single_cpph VARCHAR(15) DEFAULT NULL, single_rank_number VARCHAR(10) DEFAULT NULL, single_french_rank_number VARCHAR(10) DEFAULT NULL, single_rank_name VARCHAR(5) DEFAULT NULL, double_cpph VARCHAR(15) DEFAULT NULL, double_rank_number VARCHAR(10) DEFAULT NULL, double_french_rank_number VARCHAR(10) DEFAULT NULL, double_rank_name VARCHAR(5) DEFAULT NULL, mixed_cpph VARCHAR(15) DEFAULT NULL, mixed_rank_number VARCHAR(10) DEFAULT NULL, mixed_french_rank_number VARCHAR(10) DEFAULT NULL, mixed_rank_name VARCHAR(5) DEFAULT NULL, cpphsum VARCHAR(15) DEFAULT NULL, INDEX IDX_87DF3193A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ffbad_stat ADD CONSTRAINT FK_87DF3193A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ffbad_stat DROP FOREIGN KEY FK_87DF3193A76ED395');
        $this->addSql('DROP TABLE ffbad_stat');
    }
}
