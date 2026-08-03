<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260718125856 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE review DROP avatar, CHANGE badge badge VARCHAR(255) NOT NULL, CHANGE reviews_count reviews_count INT NOT NULL, CHANGE photos_count photos_count INT NOT NULL, CHANGE date date VARCHAR(255) NOT NULL, CHANGE visit_date visit_date VARCHAR(255) NOT NULL, CHANGE product product VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE review ADD avatar VARCHAR(10) DEFAULT NULL, CHANGE badge badge VARCHAR(255) DEFAULT NULL, CHANGE reviews_count reviews_count INT DEFAULT NULL, CHANGE photos_count photos_count INT DEFAULT NULL, CHANGE date date VARCHAR(255) DEFAULT NULL, CHANGE visit_date visit_date VARCHAR(255) DEFAULT NULL, CHANGE product product VARCHAR(255) DEFAULT NULL');
    }
}
