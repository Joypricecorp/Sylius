<?php

namespace Sylius\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161104163628 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE toro_cms_resource_viewer (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, resourceName VARCHAR(255) NOT NULL, resourceId INT NOT NULL, ip VARCHAR(255) DEFAULT NULL, meta LONGTEXT NOT NULL COMMENT \'(DC2Type:json_array)\', created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_EE362BB2A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE toro_cms_resource_viewer ADD CONSTRAINT FK_EE362BB2A76ED395 FOREIGN KEY (user_id) REFERENCES sylius_shop_user (id)');
        $this->addSql('ALTER TABLE toro_cms_page_translation CHANGE body body LONGTEXT DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE toro_cms_resource_viewer');
        $this->addSql('ALTER TABLE toro_cms_page_translation CHANGE body body LONGTEXT NOT NULL COLLATE utf8_unicode_ci');
    }
}
