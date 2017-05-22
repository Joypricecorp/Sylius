<?php

namespace Sylius\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170124072147 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE UNIQUE INDEX slug_uidx ON toro_cms_page_translation (locale, slug)');
        $this->addSql('ALTER TABLE toro_cms_post_translation CHANGE vdo_path vdoPath VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX slug_uidx ON toro_cms_post_translation (locale, slug)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX slug_uidx ON toro_cms_page_translation');
        $this->addSql('DROP INDEX slug_uidx ON toro_cms_post_translation');
        $this->addSql('ALTER TABLE toro_cms_post_translation CHANGE vdopath vdo_path VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}
