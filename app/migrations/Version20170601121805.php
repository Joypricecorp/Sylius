<?php

namespace Sylius\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170601121805 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE vcare_news_subscriber (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE INDEX IDX_PUBLISHED ON toro_cms_page (published)');
        $this->addSql('CREATE INDEX IDX_PARTIAL ON toro_cms_page (partial)');
        $this->addSql('CREATE INDEX IDX_VIEWERS ON toro_cms_page (viewers)');
        $this->addSql('CREATE INDEX IDX_DELETABLE ON toro_cms_page (deletable)');
        $this->addSql('CREATE INDEX IDX_PUBLISHED ON toro_cms_post (published)');
        $this->addSql('CREATE INDEX IDX_PUBLISHED_AT ON toro_cms_post (published_at)');
        $this->addSql('CREATE INDEX IDX_VIEWERS ON toro_cms_post (viewers)');
        $this->addSql('CREATE INDEX IDX_LIKE_TOTAL ON toro_cms_post (like_total)');
        $this->addSql('CREATE INDEX IDX_DISLIKE_TOTAL ON toro_cms_post (dislike_total)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE vcare_news_subscriber');
        $this->addSql('DROP INDEX IDX_PUBLISHED ON toro_cms_page');
        $this->addSql('DROP INDEX IDX_PARTIAL ON toro_cms_page');
        $this->addSql('DROP INDEX IDX_VIEWERS ON toro_cms_page');
        $this->addSql('DROP INDEX IDX_DELETABLE ON toro_cms_page');
        $this->addSql('DROP INDEX IDX_PUBLISHED ON toro_cms_post');
        $this->addSql('DROP INDEX IDX_PUBLISHED_AT ON toro_cms_post');
        $this->addSql('DROP INDEX IDX_VIEWERS ON toro_cms_post');
        $this->addSql('DROP INDEX IDX_LIKE_TOTAL ON toro_cms_post');
        $this->addSql('DROP INDEX IDX_DISLIKE_TOTAL ON toro_cms_post');
    }
}
