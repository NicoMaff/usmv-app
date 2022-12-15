<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221215120211 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD main_image_name VARCHAR(255) DEFAULT NULL, ADD main_image_url VARCHAR(255) DEFAULT NULL, ADD first_additional_image_name VARCHAR(255) DEFAULT NULL, ADD first_additional_image_url VARCHAR(255) DEFAULT NULL, ADD second_additional_image_name VARCHAR(255) DEFAULT NULL, ADD second_additional_image_url VARCHAR(255) DEFAULT NULL, ADD third_additional_image_name VARCHAR(255) DEFAULT NULL, ADD third_additional_image_url VARCHAR(255) DEFAULT NULL, ADD visible TINYINT(1) NOT NULL, DROP url_image_main, DROP url_image_additional_1, DROP url_image_additional_2, DROP url_image_additional_3');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD url_image_main VARCHAR(255) DEFAULT NULL, ADD url_image_additional_1 VARCHAR(255) DEFAULT NULL, ADD url_image_additional_2 VARCHAR(255) DEFAULT NULL, ADD url_image_additional_3 VARCHAR(255) DEFAULT NULL, DROP main_image_name, DROP main_image_url, DROP first_additional_image_name, DROP first_additional_image_url, DROP second_additional_image_name, DROP second_additional_image_url, DROP third_additional_image_name, DROP third_additional_image_url, DROP visible');
    }
}
