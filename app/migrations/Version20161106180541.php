<?php

namespace Sylius\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161106180541 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE toro_post_tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX TAG_NAME (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE toro_cms_post (id INT AUTO_INCREMENT NOT NULL, channel_id INT NOT NULL, taxon_id INT NOT NULL, options_id INT DEFAULT NULL, published TINYINT(1) NOT NULL, publishedAt DATETIME DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, createdBy_id INT DEFAULT NULL, updatedBy_id INT DEFAULT NULL, INDEX IDX_FC8887183174800F (createdBy_id), INDEX IDX_FC88871865FF1AEC (updatedBy_id), INDEX IDX_FC88871872F5A1AA (channel_id), INDEX IDX_FC888718DE13F470 (taxon_id), UNIQUE INDEX UNIQ_FC8887183ADB05F1 (options_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE toro_cms_post_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT NOT NULL, slug VARCHAR(60) NOT NULL, title VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, body LONGTEXT DEFAULT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_3B0A35F42C2AC5D3 (translatable_id), UNIQUE INDEX toro_cms_post_translation_uniq_trans (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE toro_post_tags (post_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_8590D214B89032C (post_id), INDEX IDX_8590D21BAD26311 (tag_id), PRIMARY KEY(post_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE toro_cms_post ADD CONSTRAINT FK_FC8887183174800F FOREIGN KEY (createdBy_id) REFERENCES sylius_admin_user (id)');
        $this->addSql('ALTER TABLE toro_cms_post ADD CONSTRAINT FK_FC88871865FF1AEC FOREIGN KEY (updatedBy_id) REFERENCES sylius_admin_user (id)');
        $this->addSql('ALTER TABLE toro_cms_post ADD CONSTRAINT FK_FC88871872F5A1AA FOREIGN KEY (channel_id) REFERENCES sylius_channel (id)');
        $this->addSql('ALTER TABLE toro_cms_post ADD CONSTRAINT FK_FC888718DE13F470 FOREIGN KEY (taxon_id) REFERENCES sylius_taxon (id)');
        $this->addSql('ALTER TABLE toro_cms_post ADD CONSTRAINT FK_FC8887183ADB05F1 FOREIGN KEY (options_id) REFERENCES toro_cms_option (id)');
        $this->addSql('ALTER TABLE toro_cms_post_translation ADD CONSTRAINT FK_3B0A35F42C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES toro_cms_post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE toro_post_tags ADD CONSTRAINT FK_8590D214B89032C FOREIGN KEY (post_id) REFERENCES toro_cms_post_translation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE toro_post_tags ADD CONSTRAINT FK_8590D21BAD26311 FOREIGN KEY (tag_id) REFERENCES toro_post_tag (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE toro_post_tags DROP FOREIGN KEY FK_8590D21BAD26311');
        $this->addSql('ALTER TABLE toro_cms_post_translation DROP FOREIGN KEY FK_3B0A35F42C2AC5D3');
        $this->addSql('ALTER TABLE toro_post_tags DROP FOREIGN KEY FK_8590D214B89032C');
        $this->addSql('DROP TABLE toro_post_tag');
        $this->addSql('DROP TABLE toro_cms_post');
        $this->addSql('DROP TABLE toro_cms_post_translation');
        $this->addSql('DROP TABLE toro_post_tags');
    }
}
