<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210102034516 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_BA388B76C755722');
        $this->addSql('DROP INDEX IDX_BA388B74584665A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__cart AS SELECT id, buyer_id, product_id, added_date FROM cart');
        $this->addSql('DROP TABLE cart');
        $this->addSql('CREATE TABLE cart (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, buyer_id INTEGER DEFAULT NULL, product_id INTEGER DEFAULT NULL, added_date DATETIME NOT NULL, quantity INTEGER NOT NULL, CONSTRAINT FK_BA388B76C755722 FOREIGN KEY (buyer_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_BA388B74584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO cart (id, buyer_id, product_id, added_date) SELECT id, buyer_id, product_id, added_date FROM __temp__cart');
        $this->addSql('DROP TABLE __temp__cart');
        $this->addSql('CREATE INDEX IDX_BA388B76C755722 ON cart (buyer_id)');
        $this->addSql('CREATE INDEX IDX_BA388B74584665A ON cart (product_id)');
        $this->addSql('DROP INDEX IDX_D34A04AD12469DE2');
        $this->addSql('DROP INDEX IDX_D34A04AD8DE820D9');
        $this->addSql('CREATE TEMPORARY TABLE __temp__product AS SELECT id, category_id, seller_id, product_name, product_price, product_date FROM product');
        $this->addSql('DROP TABLE product');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER DEFAULT NULL, seller_id INTEGER DEFAULT NULL, product_name VARCHAR(255) NOT NULL COLLATE BINARY, product_price DOUBLE PRECISION NOT NULL, product_date DATETIME NOT NULL, CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D34A04AD8DE820D9 FOREIGN KEY (seller_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO product (id, category_id, seller_id, product_name, product_price, product_date) SELECT id, category_id, seller_id, product_name, product_price, product_date FROM __temp__product');
        $this->addSql('DROP TABLE __temp__product');
        $this->addSql('CREATE INDEX IDX_D34A04AD12469DE2 ON product (category_id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD8DE820D9 ON product (seller_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_BA388B76C755722');
        $this->addSql('DROP INDEX IDX_BA388B74584665A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__cart AS SELECT id, buyer_id, product_id, added_date FROM cart');
        $this->addSql('DROP TABLE cart');
        $this->addSql('CREATE TABLE cart (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, buyer_id INTEGER DEFAULT NULL, product_id INTEGER DEFAULT NULL, added_date DATETIME NOT NULL)');
        $this->addSql('INSERT INTO cart (id, buyer_id, product_id, added_date) SELECT id, buyer_id, product_id, added_date FROM __temp__cart');
        $this->addSql('DROP TABLE __temp__cart');
        $this->addSql('CREATE INDEX IDX_BA388B76C755722 ON cart (buyer_id)');
        $this->addSql('CREATE INDEX IDX_BA388B74584665A ON cart (product_id)');
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
