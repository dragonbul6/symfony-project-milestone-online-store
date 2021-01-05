<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201229004506 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, buyer_id INTEGER DEFAULT NULL, product_id_id INTEGER DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_BA388B76C755722 ON cart (buyer_id)');
        $this->addSql('CREATE INDEX IDX_BA388B7DE18E50B ON cart (product_id_id)');
        $this->addSql('DROP INDEX IDX_D34A04AD8DE820D9');
        $this->addSql('DROP INDEX IDX_D34A04AD12469DE2');
        $this->addSql('CREATE TEMPORARY TABLE __temp__product AS SELECT id, category_id, seller_id, product_name, product_price, product_date FROM product');
        $this->addSql('DROP TABLE product');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER DEFAULT NULL, seller_id INTEGER DEFAULT NULL, product_name VARCHAR(255) NOT NULL COLLATE BINARY, product_price DOUBLE PRECISION NOT NULL, product_date DATETIME NOT NULL, CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D34A04AD8DE820D9 FOREIGN KEY (seller_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO product (id, category_id, seller_id, product_name, product_price, product_date) SELECT id, category_id, seller_id, product_name, product_price, product_date FROM __temp__product');
        $this->addSql('DROP TABLE __temp__product');
        $this->addSql('CREATE INDEX IDX_D34A04AD8DE820D9 ON product (seller_id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD12469DE2 ON product (category_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP INDEX IDX_D34A04AD12469DE2');
        $this->addSql('DROP INDEX IDX_D34A04AD8DE820D9');
        $this->addSql('CREATE TEMPORARY TABLE __temp__product AS SELECT id, category_id, seller_id, product_name, product_price, product_date FROM product');
        $this->addSql('DROP TABLE product');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER DEFAULT NULL, seller_id INTEGER DEFAULT NULL, product_name VARCHAR(255) NOT NULL, product_price DOUBLE PRECISION NOT NULL, product_date DATETIME NOT NULL)');
        $this->addSql('INSERT INTO product (id, category_id, seller_id, product_name, product_price, product_date) SELECT id, category_id, seller_id, product_name, product_price, product_date FROM __temp__product');
        $this->addSql('DROP TABLE __temp__product');
        $this->addSql('CREATE INDEX IDX_D34A04AD12469DE2 ON product (category_id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD8DE820D9 ON product (seller_id)');
    }
}
