<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230513153913 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__products AS SELECT name FROM products');
        $this->addSql('DROP TABLE products');
        $this->addSql('CREATE TABLE products (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(128) NOT NULL, product_index SMALLINT NOT NULL)');
        $this->addSql('INSERT INTO products (name) SELECT name FROM __temp__products');
        $this->addSql('DROP TABLE __temp__products');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__products AS SELECT name FROM products');
        $this->addSql('DROP TABLE products');
        $this->addSql('CREATE TABLE products ("index" BIGINT NOT NULL, name VARCHAR(128) NOT NULL, PRIMARY KEY("index"))');
        $this->addSql('INSERT INTO products (name) SELECT name FROM __temp__products');
        $this->addSql('DROP TABLE __temp__products');
    }
}
