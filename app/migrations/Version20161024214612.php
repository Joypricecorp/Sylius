<?php

namespace Sylius\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161024214612 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_customer DROP FOREIGN KEY FK_7E82D5E64D4CFF2B');
        $this->addSql('ALTER TABLE sylius_customer DROP FOREIGN KEY FK_7E82D5E679D0C0E4');
        $this->addSql('DROP INDEX UNIQ_7E82D5E679D0C0E4 ON sylius_customer');
        $this->addSql('DROP INDEX UNIQ_7E82D5E64D4CFF2B ON sylius_customer');
        $this->addSql('ALTER TABLE sylius_customer ADD default_address_id INT DEFAULT NULL, DROP shipping_address_id, DROP billing_address_id');
        $this->addSql('ALTER TABLE sylius_customer ADD CONSTRAINT FK_7E82D5E6BD94FB16 FOREIGN KEY (default_address_id) REFERENCES sylius_address (id) ON DELETE SET NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7E82D5E6BD94FB16 ON sylius_customer (default_address_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE sylius_customer DROP FOREIGN KEY FK_7E82D5E6BD94FB16');
        $this->addSql('DROP INDEX UNIQ_7E82D5E6BD94FB16 ON sylius_customer');
        $this->addSql('ALTER TABLE sylius_customer ADD billing_address_id INT DEFAULT NULL, CHANGE default_address_id shipping_address_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_customer ADD CONSTRAINT FK_7E82D5E64D4CFF2B FOREIGN KEY (shipping_address_id) REFERENCES sylius_address (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE sylius_customer ADD CONSTRAINT FK_7E82D5E679D0C0E4 FOREIGN KEY (billing_address_id) REFERENCES sylius_address (id) ON DELETE SET NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7E82D5E679D0C0E4 ON sylius_customer (billing_address_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7E82D5E64D4CFF2B ON sylius_customer (shipping_address_id)');
    }
}
