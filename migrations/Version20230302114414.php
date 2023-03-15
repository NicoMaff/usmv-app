<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230302114414 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE result (id INT AUTO_INCREMENT NOT NULL, tournament_registration_id INT DEFAULT NULL, single_stage_reached VARCHAR(100) DEFAULT NULL, double_stage_reached VARCHAR(100) DEFAULT NULL, mixed_stage_reached VARCHAR(100) DEFAULT NULL, are_results_validated TINYINT(1) DEFAULT NULL, comment LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_136AC1136B300921 (tournament_registration_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE result ADD CONSTRAINT FK_136AC1136B300921 FOREIGN KEY (tournament_registration_id) REFERENCES tournament_registration (id)');
        $this->addSql('ALTER TABLE tournament_registration DROP single_stage_reached, DROP double_stage_reached, DROP mixed_stage_reached, DROP are_results_validated');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE result DROP FOREIGN KEY FK_136AC1136B300921');
        $this->addSql('DROP TABLE result');
        $this->addSql('ALTER TABLE tournament_registration ADD single_stage_reached VARCHAR(20) DEFAULT NULL, ADD double_stage_reached VARCHAR(20) DEFAULT NULL, ADD mixed_stage_reached VARCHAR(20) DEFAULT NULL, ADD are_results_validated TINYINT(1) DEFAULT NULL');
    }
}
