<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230514201100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE products_category (products_id INTEGER NOT NULL, category_id INTEGER NOT NULL, PRIMARY KEY(products_id, category_id), CONSTRAINT FK_134D09726C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_134D097212469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_134D09726C8A81A9 ON products_category (products_id)');
        $this->addSql('CREATE INDEX IDX_134D097212469DE2 ON products_category (category_id)');
        $this->addSql('DROP TABLE products_products');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE products_products (products_source INTEGER NOT NULL, products_target INTEGER NOT NULL, PRIMARY KEY(products_source, products_target), CONSTRAINT FK_A6BB4AE9D9B9F459 FOREIGN KEY (products_source) REFERENCES products (id) ON UPDATE NO ACTION ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A6BB4AE9C05CA4D6 FOREIGN KEY (products_target) REFERENCES products (id) ON UPDATE NO ACTION ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_A6BB4AE9C05CA4D6 ON products_products (products_target)');
        $this->addSql('CREATE INDEX IDX_A6BB4AE9D9B9F459 ON products_products (products_source)');
        $this->addSql('DROP TABLE products_category');
    }
}
