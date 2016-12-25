<?php

namespace Sylius\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161026021644 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE toro_cms_option DROP FOREIGN KEY FK_6168E31D3174800F');
        $this->addSql('ALTER TABLE toro_cms_option DROP FOREIGN KEY FK_6168E31D65FF1AEC');
        $this->addSql('DROP INDEX IDX_6168E31D3174800F ON toro_cms_option');
        $this->addSql('DROP INDEX IDX_6168E31D65FF1AEC ON toro_cms_option');
        $this->addSql('ALTER TABLE toro_cms_option DROP createdBy_id, DROP updatedBy_id');
        $this->addSql('ALTER TABLE toro_cms_page DROP FOREIGN KEY FK_B2085DB53174800F');
        $this->addSql('ALTER TABLE toro_cms_page DROP FOREIGN KEY FK_B2085DB565FF1AEC');
        $this->addSql('ALTER TABLE toro_cms_page ADD CONSTRAINT FK_B2085DB53174800F FOREIGN KEY (createdBy_id) REFERENCES sylius_admin_user (id)');
        $this->addSql('ALTER TABLE toro_cms_page ADD CONSTRAINT FK_B2085DB565FF1AEC FOREIGN KEY (updatedBy_id) REFERENCES sylius_admin_user (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE toro_cms_option ADD createdBy_id INT DEFAULT NULL, ADD updatedBy_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE toro_cms_option ADD CONSTRAINT FK_6168E31D3174800F FOREIGN KEY (createdBy_id) REFERENCES sylius_shop_user (id)');
        $this->addSql('ALTER TABLE toro_cms_option ADD CONSTRAINT FK_6168E31D65FF1AEC FOREIGN KEY (updatedBy_id) REFERENCES sylius_shop_user (id)');
        $this->addSql('CREATE INDEX IDX_6168E31D3174800F ON toro_cms_option (createdBy_id)');
        $this->addSql('CREATE INDEX IDX_6168E31D65FF1AEC ON toro_cms_option (updatedBy_id)');
        $this->addSql('ALTER TABLE toro_cms_page DROP FOREIGN KEY FK_B2085DB53174800F');
        $this->addSql('ALTER TABLE toro_cms_page DROP FOREIGN KEY FK_B2085DB565FF1AEC');
        $this->addSql('ALTER TABLE toro_cms_page ADD CONSTRAINT FK_B2085DB53174800F FOREIGN KEY (createdBy_id) REFERENCES sylius_shop_user (id)');
        $this->addSql('ALTER TABLE toro_cms_page ADD CONSTRAINT FK_B2085DB565FF1AEC FOREIGN KEY (updatedBy_id) REFERENCES sylius_shop_user (id)');
    }
}
