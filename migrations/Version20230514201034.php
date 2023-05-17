<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230514201034 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE products_products (products_source INTEGER NOT NULL, products_target INTEGER NOT NULL, PRIMARY KEY(products_source, products_target), CONSTRAINT FK_A6BB4AE9D9B9F459 FOREIGN KEY (products_source) REFERENCES products (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A6BB4AE9C05CA4D6 FOREIGN KEY (products_target) REFERENCES products (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_A6BB4AE9D9B9F459 ON products_products (products_source)');
        $this->addSql('CREATE INDEX IDX_A6BB4AE9C05CA4D6 ON products_products (products_target)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__products AS SELECT id, name, product_index FROM products');
        $this->addSql('DROP TABLE products');
        $this->addSql('CREATE TABLE products (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(128) NOT NULL, product_index SMALLINT NOT NULL)');
        $this->addSql('INSERT INTO products (id, name, product_index) SELECT id, name, product_index FROM __temp__products');
        $this->addSql('DROP TABLE __temp__products');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B3BA5A5A212F1C5D ON products (product_index)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE products_products');
        $this->addSql('CREATE TEMPORARY TABLE __temp__products AS SELECT id, product_index, name FROM products');
        $this->addSql('DROP TABLE products');
        $this->addSql('CREATE TABLE products (id INTEGER NOT NULL, product_index SMALLINT NOT NULL, name VARCHAR(128) NOT NULL, categoryId BIGINT NOT NULL, PRIMARY KEY(id, categoryId))');
        $this->addSql('INSERT INTO products (id, product_index, name) SELECT id, product_index, name FROM __temp__products');
        $this->addSql('DROP TABLE __temp__products');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B3BA5A5A212F1C5D ON products (product_index)');
        $this->addSql('CREATE INDEX category_id_idx ON products (categoryId)');
    }
}
