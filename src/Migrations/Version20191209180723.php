<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191209180723 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE product_cart (product_id INT NOT NULL, cart_id INT NOT NULL, INDEX IDX_864BAA164584665A (product_id), INDEX IDX_864BAA161AD5CDBF (cart_id), PRIMARY KEY(product_id, cart_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_cart ADD CONSTRAINT FK_864BAA164584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_cart ADD CONSTRAINT FK_864BAA161AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD1AD5CDBF');
        $this->addSql('DROP INDEX IDX_D34A04AD1AD5CDBF ON product');
        $this->addSql('ALTER TABLE product DROP cart_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE product_cart');
        $this->addSql('ALTER TABLE product ADD cart_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD1AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD1AD5CDBF ON product (cart_id)');
    }
}
