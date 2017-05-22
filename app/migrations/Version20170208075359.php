<?php

namespace Sylius\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170208075359 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_address_log_entries CHANGE loggedat logged_at DATETIME NOT NULL, CHANGE objectid object_id VARCHAR(64) DEFAULT NULL, CHANGE objectclass object_class VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE toro_cms_option CHANGE compiledat compiled_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE toro_cms_post_like CHANGE createdat created_at DATETIME NOT NULL, CHANGE updatedat updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE toro_cms_resource_viewer CHANGE resourcename resource_name VARCHAR(255) NOT NULL, CHANGE resourceid resource_id INT NOT NULL');
        $this->addSql('CREATE INDEX idx_resource_name ON toro_cms_resource_viewer (resource_name)');
        $this->addSql('CREATE INDEX idx_resource_id ON toro_cms_resource_viewer (resource_id)');
        $this->addSql('CREATE INDEX idx_ip ON toro_cms_resource_viewer (ip)');
        $this->addSql('CREATE INDEX idx_created_at ON toro_cms_resource_viewer (created_at)');
        $this->addSql('ALTER TABLE toro_cms_page DROP FOREIGN KEY FK_B2085DB53174800F');
        $this->addSql('ALTER TABLE toro_cms_page DROP FOREIGN KEY FK_B2085DB565FF1AEC');
        $this->addSql('DROP INDEX IDX_B2085DB53174800F ON toro_cms_page');
        $this->addSql('DROP INDEX IDX_B2085DB565FF1AEC ON toro_cms_page');
        $this->addSql('ALTER TABLE toro_cms_page CHANGE createdBy_id created_by_id INT DEFAULT NULL, CHANGE updatedBy_id updated_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE toro_cms_page ADD CONSTRAINT FK_B2085DB5B03A8386 FOREIGN KEY (created_by_id) REFERENCES sylius_admin_user (id)');
        $this->addSql('ALTER TABLE toro_cms_page ADD CONSTRAINT FK_B2085DB5896DBBDE FOREIGN KEY (updated_by_id) REFERENCES sylius_admin_user (id)');
        $this->addSql('CREATE INDEX IDX_B2085DB5B03A8386 ON toro_cms_page (created_by_id)');
        $this->addSql('CREATE INDEX IDX_B2085DB5896DBBDE ON toro_cms_page (updated_by_id)');
        $this->addSql('ALTER TABLE toro_cms_post DROP FOREIGN KEY FK_FC8887183174800F');
        $this->addSql('ALTER TABLE toro_cms_post DROP FOREIGN KEY FK_FC88871865FF1AEC');
        $this->addSql('DROP INDEX IDX_FC8887183174800F ON toro_cms_post');
        $this->addSql('DROP INDEX IDX_FC88871865FF1AEC ON toro_cms_post');
        $this->addSql('ALTER TABLE toro_cms_post CHANGE createdBy_id created_by_id INT DEFAULT NULL, CHANGE updatedBy_id updated_by_id INT DEFAULT NULL, CHANGE likeTotal like_total INT NOT NULL, CHANGE dislikeTotal dislike_total INT NOT NULL, CHANGE coverId cover_id VARCHAR(255) DEFAULT NULL, CHANGE thumbId thumb_id VARCHAR(255) DEFAULT NULL, CHANGE featuredCoverId featured_cover_id VARCHAR(255) DEFAULT NULL, CHANGE publishedat published_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE toro_cms_post ADD CONSTRAINT FK_FC888718B03A8386 FOREIGN KEY (created_by_id) REFERENCES sylius_admin_user (id)');
        $this->addSql('ALTER TABLE toro_cms_post ADD CONSTRAINT FK_FC888718896DBBDE FOREIGN KEY (updated_by_id) REFERENCES sylius_admin_user (id)');
        $this->addSql('CREATE INDEX IDX_FC888718B03A8386 ON toro_cms_post (created_by_id)');
        $this->addSql('CREATE INDEX IDX_FC888718896DBBDE ON toro_cms_post (updated_by_id)');
        $this->addSql('CREATE INDEX idx_type ON toro_cms_post (type)');
        $this->addSql('ALTER TABLE toro_cms_post_translation CHANGE vdopath vdo_path VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_product CHANGE externalorderurl external_order_url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_product_manuals CHANGE fileid file_id VARCHAR(255) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_address_log_entries CHANGE logged_at loggedAt DATETIME NOT NULL, CHANGE object_id objectId VARCHAR(64) DEFAULT NULL COLLATE utf8_unicode_ci, CHANGE object_class objectClass VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE sylius_product CHANGE external_order_url externalOrderUrl VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE sylius_product_manuals CHANGE file_id fileId VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE toro_cms_option CHANGE compiled_at compiledAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE toro_cms_page DROP FOREIGN KEY FK_B2085DB5B03A8386');
        $this->addSql('ALTER TABLE toro_cms_page DROP FOREIGN KEY FK_B2085DB5896DBBDE');
        $this->addSql('DROP INDEX IDX_B2085DB5B03A8386 ON toro_cms_page');
        $this->addSql('DROP INDEX IDX_B2085DB5896DBBDE ON toro_cms_page');
        $this->addSql('ALTER TABLE toro_cms_page ADD createdBy_id INT DEFAULT NULL, ADD updatedBy_id INT DEFAULT NULL, DROP created_by_id, DROP updated_by_id');
        $this->addSql('ALTER TABLE toro_cms_page ADD CONSTRAINT FK_B2085DB53174800F FOREIGN KEY (createdBy_id) REFERENCES sylius_admin_user (id)');
        $this->addSql('ALTER TABLE toro_cms_page ADD CONSTRAINT FK_B2085DB565FF1AEC FOREIGN KEY (updatedBy_id) REFERENCES sylius_admin_user (id)');
        $this->addSql('CREATE INDEX IDX_B2085DB53174800F ON toro_cms_page (createdBy_id)');
        $this->addSql('CREATE INDEX IDX_B2085DB565FF1AEC ON toro_cms_page (updatedBy_id)');
        $this->addSql('ALTER TABLE toro_cms_post DROP FOREIGN KEY FK_FC888718B03A8386');
        $this->addSql('ALTER TABLE toro_cms_post DROP FOREIGN KEY FK_FC888718896DBBDE');
        $this->addSql('DROP INDEX IDX_FC888718B03A8386 ON toro_cms_post');
        $this->addSql('DROP INDEX IDX_FC888718896DBBDE ON toro_cms_post');
        $this->addSql('DROP INDEX idx_type ON toro_cms_post');
        $this->addSql('ALTER TABLE toro_cms_post ADD createdBy_id INT DEFAULT NULL, ADD updatedBy_id INT DEFAULT NULL, ADD coverId VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD thumbId VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD likeTotal INT NOT NULL, ADD dislikeTotal INT NOT NULL, ADD featuredCoverId VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, DROP created_by_id, DROP updated_by_id, DROP like_total, DROP dislike_total, DROP cover_id, DROP thumb_id, DROP featured_cover_id, CHANGE published_at publishedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE toro_cms_post ADD CONSTRAINT FK_FC8887183174800F FOREIGN KEY (createdBy_id) REFERENCES sylius_admin_user (id)');
        $this->addSql('ALTER TABLE toro_cms_post ADD CONSTRAINT FK_FC88871865FF1AEC FOREIGN KEY (updatedBy_id) REFERENCES sylius_admin_user (id)');
        $this->addSql('CREATE INDEX IDX_FC8887183174800F ON toro_cms_post (createdBy_id)');
        $this->addSql('CREATE INDEX IDX_FC88871865FF1AEC ON toro_cms_post (updatedBy_id)');
        $this->addSql('ALTER TABLE toro_cms_post_like CHANGE created_at createdAt DATETIME NOT NULL, CHANGE updated_at updatedAt DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE toro_cms_post_translation CHANGE vdo_path vdoPath VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
        $this->addSql('DROP INDEX idx_resource_name ON toro_cms_resource_viewer');
        $this->addSql('DROP INDEX idx_resource_id ON toro_cms_resource_viewer');
        $this->addSql('DROP INDEX idx_ip ON toro_cms_resource_viewer');
        $this->addSql('DROP INDEX idx_created_at ON toro_cms_resource_viewer');
        $this->addSql('ALTER TABLE toro_cms_resource_viewer CHANGE resource_name resourceName VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE resource_id resourceId INT NOT NULL');
    }
}
