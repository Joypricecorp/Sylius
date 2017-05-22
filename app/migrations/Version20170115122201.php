<?php

namespace Sylius\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170115122201 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sylius_product_manuals (id INT AUTO_INCREMENT NOT NULL, product_translation_id INT NOT NULL, title VARCHAR(255) NOT NULL, fileId VARCHAR(255) DEFAULT NULL, INDEX IDX_7C7D1892CFC35509 (product_translation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sylius_product_manuals ADD CONSTRAINT FK_7C7D1892CFC35509 FOREIGN KEY (product_translation_id) REFERENCES sylius_product_translation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_translation ADD data LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\'');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE sylius_product_manuals');
        $this->addSql('ALTER TABLE sylius_product_translation DROP data');
    }
}
