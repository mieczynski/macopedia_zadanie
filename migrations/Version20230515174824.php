<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230515174824 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE products_category');
        $this->addSql('CREATE TEMPORARY TABLE __temp__products AS SELECT id, name, product_index FROM products');
        $this->addSql('DROP TABLE products');
        $this->addSql('CREATE TABLE products (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category INTEGER DEFAULT NULL, name VARCHAR(128) NOT NULL, product_index SMALLINT NOT NULL, CONSTRAINT FK_B3BA5A5A64C19C1 FOREIGN KEY (category) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO products (id, name, product_index) SELECT id, name, product_index FROM __temp__products');
        $this->addSql('DROP TABLE __temp__products');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B3BA5A5A212F1C5D ON products (product_index)');
        $this->addSql('CREATE INDEX IDX_B3BA5A5A64C19C1 ON products (category)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE products_category (products_id INTEGER NOT NULL, category_id INTEGER NOT NULL, PRIMARY KEY(products_id, category_id), CONSTRAINT FK_134D09726C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id) ON UPDATE NO ACTION ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_134D097212469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON UPDATE NO ACTION ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_134D097212469DE2 ON products_category (category_id)');
        $this->addSql('CREATE INDEX IDX_134D09726C8A81A9 ON products_category (products_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__products AS SELECT id, product_index, name FROM products');
        $this->addSql('DROP TABLE products');
        $this->addSql('CREATE TABLE products (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, product_index SMALLINT NOT NULL, name VARCHAR(128) NOT NULL)');
        $this->addSql('INSERT INTO products (id, product_index, name) SELECT id, product_index, name FROM __temp__products');
        $this->addSql('DROP TABLE __temp__products');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B3BA5A5A212F1C5D ON products (product_index)');
    }
}
