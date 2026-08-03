<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260703152813 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE wishlist (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE wishlist_product (wishlist_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_4C46D2D7FB8E54CD (wishlist_id), INDEX IDX_4C46D2D74584665A (product_id), PRIMARY KEY (wishlist_id, product_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE wishlist_product ADD CONSTRAINT FK_4C46D2D7FB8E54CD FOREIGN KEY (wishlist_id) REFERENCES wishlist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE wishlist_product ADD CONSTRAINT FK_4C46D2D74584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE whishlists_product DROP FOREIGN KEY `FK_24E90AE74584665A`');
        $this->addSql('ALTER TABLE whishlists_product DROP FOREIGN KEY `FK_24E90AE7CFF505C0`');
        $this->addSql('DROP TABLE whishlists');
        $this->addSql('DROP TABLE whishlists_product');
        $this->addSql('ALTER TABLE user ADD last_login_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE whishlists (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE whishlists_product (whishlists_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_24E90AE7CFF505C0 (whishlists_id), INDEX IDX_24E90AE74584665A (product_id), PRIMARY KEY (whishlists_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE whishlists_product ADD CONSTRAINT `FK_24E90AE74584665A` FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE whishlists_product ADD CONSTRAINT `FK_24E90AE7CFF505C0` FOREIGN KEY (whishlists_id) REFERENCES whishlists (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE wishlist_product DROP FOREIGN KEY FK_4C46D2D7FB8E54CD');
        $this->addSql('ALTER TABLE wishlist_product DROP FOREIGN KEY FK_4C46D2D74584665A');
        $this->addSql('DROP TABLE wishlist');
        $this->addSql('DROP TABLE wishlist_product');
        $this->addSql('ALTER TABLE `user` DROP last_login_at');
    }
}
