<?php

namespace Sylius\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161011134227 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE sylius_customer_group');
        $this->addSql('CREATE TABLE sylius_customer_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE orm_routes');
        $this->addSql('DROP TABLE sylius_channel_taxon');
        $this->addSql('DROP TABLE sylius_group');
        $this->addSql('ALTER TABLE sylius_customer ADD customer_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_customer ADD CONSTRAINT FK_7E82D5E6D2919A68 FOREIGN KEY (customer_group_id) REFERENCES sylius_customer_group (id)');
        $this->addSql('CREATE INDEX IDX_7E82D5E6D2919A68 ON sylius_customer (customer_group_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_customer DROP FOREIGN KEY FK_7E82D5E6D2919A68');
        $this->addSql('CREATE TABLE orm_routes (id INT AUTO_INCREMENT NOT NULL, host VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, schemes LONGTEXT NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\', methods LONGTEXT NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\', defaults LONGTEXT NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\', requirements LONGTEXT NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\', options LONGTEXT NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\', condition_expr VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, variablePattern VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, staticPrefix VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, position INT NOT NULL, UNIQUE INDEX UNIQ_5793FC5E237E06 (name), INDEX name_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_channel_taxon (channel_id INT NOT NULL, taxon_id INT NOT NULL, INDEX IDX_E11F1D72F5A1AA (channel_id), INDEX IDX_E11F1DDE13F470 (taxon_id), PRIMARY KEY(channel_id, taxon_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sylius_channel_taxon ADD CONSTRAINT FK_E11F1D72F5A1AA FOREIGN KEY (channel_id) REFERENCES sylius_channel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_channel_taxon ADD CONSTRAINT FK_E11F1DDE13F470 FOREIGN KEY (taxon_id) REFERENCES sylius_taxon (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE sylius_customer_group');
        $this->addSql('DROP INDEX IDX_7E82D5E6D2919A68 ON sylius_customer');
        $this->addSql('ALTER TABLE sylius_customer DROP customer_group_id');
    }
}
