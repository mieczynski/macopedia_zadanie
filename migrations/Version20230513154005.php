<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230513154005 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
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
        $this->addSql('CREATE TEMPORARY TABLE __temp__products AS SELECT id, product_index, name FROM products');
        $this->addSql('DROP TABLE products');
        $this->addSql('CREATE TABLE products (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, product_index SMALLINT NOT NULL, name VARCHAR(128) NOT NULL)');
        $this->addSql('INSERT INTO products (id, product_index, name) SELECT id, product_index, name FROM __temp__products');
        $this->addSql('DROP TABLE __temp__products');
    }
}
