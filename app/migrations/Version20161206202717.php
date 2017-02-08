<?php

namespace Sylius\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161206202717 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE sylius_exchange_rate (id INT AUTO_INCREMENT NOT NULL, source_currency INT NOT NULL, target_currency INT NOT NULL, ratio NUMERIC(10, 5) NOT NULL, INDEX IDX_5F52B852A76BEED (source_currency), INDEX IDX_5F52B85B3FD5856 (target_currency), UNIQUE INDEX UNIQ_5F52B852A76BEEDB3FD5856 (source_currency, target_currency), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_channel_pricing (id INT AUTO_INCREMENT NOT NULL, product_variant_id INT NOT NULL, channel_id INT NOT NULL, price INT NOT NULL, INDEX IDX_7801820CA80EF684 (product_variant_id), INDEX IDX_7801820C72F5A1AA (channel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_payment_method_channels (payment_method_id INT NOT NULL, channel_id INT NOT NULL, INDEX IDX_543AC0CC5AA1164F (payment_method_id), INDEX IDX_543AC0CC72F5A1AA (channel_id), PRIMARY KEY(payment_method_id, channel_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_product_taxon (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, taxon_id INT NOT NULL, position INT NOT NULL, INDEX IDX_169C6CD94584665A (product_id), INDEX IDX_169C6CD9DE13F470 (taxon_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_shipping_method_channels (shipping_method_id INT NOT NULL, channel_id INT NOT NULL, INDEX IDX_2D9833355F7D6850 (shipping_method_id), INDEX IDX_2D98333572F5A1AA (channel_id), PRIMARY KEY(shipping_method_id, channel_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE toro_cms_post_like (id INT AUTO_INCREMENT NOT NULL, liked TINYINT(1) NOT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sylius_exchange_rate ADD CONSTRAINT FK_5F52B852A76BEED FOREIGN KEY (source_currency) REFERENCES sylius_currency (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_exchange_rate ADD CONSTRAINT FK_5F52B85B3FD5856 FOREIGN KEY (target_currency) REFERENCES sylius_currency (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_channel_pricing ADD CONSTRAINT FK_7801820CA80EF684 FOREIGN KEY (product_variant_id) REFERENCES sylius_product_variant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_channel_pricing ADD CONSTRAINT FK_7801820C72F5A1AA FOREIGN KEY (channel_id) REFERENCES sylius_channel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_payment_method_channels ADD CONSTRAINT FK_543AC0CC5AA1164F FOREIGN KEY (payment_method_id) REFERENCES sylius_payment_method (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_payment_method_channels ADD CONSTRAINT FK_543AC0CC72F5A1AA FOREIGN KEY (channel_id) REFERENCES sylius_channel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_taxon ADD CONSTRAINT FK_169C6CD94584665A FOREIGN KEY (product_id) REFERENCES sylius_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_product_taxon ADD CONSTRAINT FK_169C6CD9DE13F470 FOREIGN KEY (taxon_id) REFERENCES sylius_taxon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_shipping_method_channels ADD CONSTRAINT FK_2D9833355F7D6850 FOREIGN KEY (shipping_method_id) REFERENCES sylius_shipping_method (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_shipping_method_channels ADD CONSTRAINT FK_2D98333572F5A1AA FOREIGN KEY (channel_id) REFERENCES sylius_channel (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE ext_log_entries');
        $this->addSql('DROP TABLE sylius_channel_payment_methods');
        $this->addSql('DROP TABLE sylius_channel_shipping_methods');
        $this->addSql('DROP TABLE sylius_email');
        $this->addSql('ALTER TABLE sylius_currency DROP exchange_rate, DROP enabled');
        $this->addSql('CREATE UNIQUE INDEX product_association_idx ON sylius_product_association (product_id, association_type_id)');
        $this->addSql('ALTER TABLE sylius_product_attribute ADD position INT NOT NULL');
        $this->addSql('ALTER TABLE sylius_province CHANGE country_id country_id INT NOT NULL');
        $this->addSql('DROP INDEX permalink_uidx ON sylius_taxon_translation');
        $this->addSql('ALTER TABLE sylius_taxon_translation DROP permalink');
        $this->addSql('CREATE UNIQUE INDEX slug_uidx ON sylius_taxon_translation (locale, slug)');
        $this->addSql('ALTER TABLE sylius_channel DROP FOREIGN KEY FK_16C8119EECD792C0');
        $this->addSql('DROP INDEX IDX_16C8119EECD792C0 ON sylius_channel');
        $this->addSql('ALTER TABLE sylius_channel CHANGE default_currency_id base_currency_id INT NOT NULL');
        $this->addSql('ALTER TABLE sylius_channel ADD CONSTRAINT FK_16C8119E3101778E FOREIGN KEY (base_currency_id) REFERENCES sylius_currency (id)');
        $this->addSql('CREATE INDEX IDX_16C8119E3101778E ON sylius_channel (base_currency_id)');
        $this->addSql('ALTER TABLE sylius_order ADD customer_ip VARCHAR(255) DEFAULT NULL, DROP exchange_rate, CHANGE completed_at checkout_completed_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_payment_method ADD position INT NOT NULL');
        $this->addSql('ALTER TABLE sylius_product_variant DROP price, DROP pricing_calculator, DROP pricing_configuration, CHANGE original_price shipping_category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_product_variant ADD CONSTRAINT FK_A29B5239E2D1A41 FOREIGN KEY (shipping_category_id) REFERENCES sylius_shipping_category (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_A29B5239E2D1A41 ON sylius_product_variant (shipping_category_id)');
        $this->addSql('ALTER TABLE toro_cms_page ADD viewers INT NOT NULL');
        $this->addSql('ALTER TABLE toro_cms_post ADD likeTotal INT NOT NULL, ADD dislikeTotal INT NOT NULL, ADD viewers INT NOT NULL');
        $this->addSql('ALTER TABLE sylius_product DROP FOREIGN KEY FK_677B9B749E2D1A41');
        $this->addSql('DROP INDEX IDX_677B9B749E2D1A41 ON sylius_product');
        $this->addSql('ALTER TABLE sylius_product DROP shipping_category_id');

        $this->addSql('INSERT INTO sylius_product_taxon (product_id, taxon_id, position) VALUES (4, 2, 0)');
        $this->addSql('INSERT INTO sylius_product_taxon (product_id, taxon_id, position) VALUES (5, 2, 0)');
        $this->addSql('INSERT INTO sylius_product_taxon (product_id, taxon_id, position) VALUES (7, 2, 0)');
        $this->addSql('INSERT INTO sylius_product_taxon (product_id, taxon_id, position) VALUES (8, 3, 0)');
        $this->addSql('INSERT INTO sylius_product_taxon (product_id, taxon_id, position) VALUES (10, 3, 0)');
        $this->addSql('INSERT INTO sylius_product_taxon (product_id, taxon_id, position) VALUES (11, 4, 0)');
        $this->addSql('INSERT INTO sylius_product_taxon (product_id, taxon_id, position) VALUES (12, 4, 0)');
        $this->addSql('INSERT INTO sylius_product_taxon (product_id, taxon_id, position) VALUES (2, 5, 0)');
        $this->addSql('INSERT INTO sylius_product_taxon (product_id, taxon_id, position) VALUES (3, 5, 0)');

        $this->addSql('INSERT INTO sylius_channel_pricing (product_variant_id, channel_id, price) VALUES (1, 1, 99000)');
        $this->addSql('INSERT INTO sylius_channel_pricing (product_variant_id, channel_id, price) VALUES (2, 1, 250000)');
        $this->addSql('INSERT INTO sylius_channel_pricing (product_variant_id, channel_id, price) VALUES (3, 1, 75000)');
        $this->addSql('INSERT INTO sylius_channel_pricing (product_variant_id, channel_id, price) VALUES (4, 1, 395000)');
        $this->addSql('INSERT INTO sylius_channel_pricing (product_variant_id, channel_id, price) VALUES (5, 1, 420000)');
        $this->addSql('INSERT INTO sylius_channel_pricing (product_variant_id, channel_id, price) VALUES (7, 1, 140000)');
        $this->addSql('INSERT INTO sylius_channel_pricing (product_variant_id, channel_id, price) VALUES (8, 1, 320000)');
        $this->addSql('INSERT INTO sylius_channel_pricing (product_variant_id, channel_id, price) VALUES (10, 1, 490000)');
        $this->addSql('INSERT INTO sylius_channel_pricing (product_variant_id, channel_id, price) VALUES (11, 1, 295000)');
        $this->addSql('INSERT INTO sylius_channel_pricing (product_variant_id, channel_id, price) VALUES (12, 1, 395000)');
        $this->addSql('INSERT INTO sylius_channel_pricing (product_variant_id, channel_id, price) VALUES (13, 1, 250)');
        $this->addSql('INSERT INTO sylius_channel_pricing (product_variant_id, channel_id, price) VALUES (15, 1, 140000)');
        $this->addSql('INSERT INTO sylius_channel_pricing (product_variant_id, channel_id, price) VALUES (16, 1, 150000)');
        $this->addSql('INSERT INTO sylius_channel_pricing (product_variant_id, channel_id, price) VALUES (17, 1, 166500)');
        $this->addSql('INSERT INTO sylius_channel_pricing (product_variant_id, channel_id, price) VALUES (18, 1, 155400)');
        $this->addSql('INSERT INTO sylius_channel_pricing (product_variant_id, channel_id, price) VALUES (19, 1, 76600)');
        $this->addSql('INSERT INTO sylius_channel_pricing (product_variant_id, channel_id, price) VALUES (20, 1, 11000)');
        $this->addSql('INSERT INTO sylius_channel_pricing (product_variant_id, channel_id, price) VALUES (21, 1, 11000)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ext_log_entries (id INT AUTO_INCREMENT NOT NULL, action VARCHAR(8) NOT NULL COLLATE utf8_unicode_ci, logged_at DATETIME NOT NULL, object_id VARCHAR(64) DEFAULT NULL COLLATE utf8_unicode_ci, object_class VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, version INT NOT NULL, data LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\', username VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, INDEX log_class_lookup_idx (object_class), INDEX log_date_lookup_idx (logged_at), INDEX log_user_lookup_idx (username), INDEX log_version_lookup_idx (object_id, object_class, version), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_channel_payment_methods (channel_id INT NOT NULL, payment_method_id INT NOT NULL, INDEX IDX_B0C0002B72F5A1AA (channel_id), INDEX IDX_B0C0002B5AA1164F (payment_method_id), PRIMARY KEY(channel_id, payment_method_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_channel_shipping_methods (channel_id INT NOT NULL, shipping_method_id INT NOT NULL, INDEX IDX_6858B18E72F5A1AA (channel_id), INDEX IDX_6858B18E5F7D6850 (shipping_method_id), PRIMARY KEY(channel_id, shipping_method_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sylius_email (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, template VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, enabled TINYINT(1) NOT NULL, subject VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, sender_name VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, sender_address VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, content LONGTEXT DEFAULT NULL COLLATE utf8_unicode_ci, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_732D4E1577153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sylius_channel_payment_methods ADD CONSTRAINT FK_B0C0002B5AA1164F FOREIGN KEY (payment_method_id) REFERENCES sylius_payment_method (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_channel_payment_methods ADD CONSTRAINT FK_B0C0002B72F5A1AA FOREIGN KEY (channel_id) REFERENCES sylius_channel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_channel_shipping_methods ADD CONSTRAINT FK_6858B18E5F7D6850 FOREIGN KEY (shipping_method_id) REFERENCES sylius_shipping_method (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sylius_channel_shipping_methods ADD CONSTRAINT FK_6858B18E72F5A1AA FOREIGN KEY (channel_id) REFERENCES sylius_channel (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE sylius_exchange_rate');
        $this->addSql('DROP TABLE sylius_channel_pricing');
        $this->addSql('DROP TABLE sylius_payment_method_channels');
        $this->addSql('DROP TABLE sylius_product_taxon');
        $this->addSql('DROP TABLE sylius_shipping_method_channels');
        $this->addSql('DROP TABLE toro_cms_post_like');
        $this->addSql('ALTER TABLE sylius_channel DROP FOREIGN KEY FK_16C8119E3101778E');
        $this->addSql('DROP INDEX IDX_16C8119E3101778E ON sylius_channel');
        $this->addSql('ALTER TABLE sylius_channel CHANGE base_currency_id default_currency_id INT NOT NULL');
        $this->addSql('ALTER TABLE sylius_channel ADD CONSTRAINT FK_16C8119EECD792C0 FOREIGN KEY (default_currency_id) REFERENCES sylius_currency (id)');
        $this->addSql('CREATE INDEX IDX_16C8119EECD792C0 ON sylius_channel (default_currency_id)');
        $this->addSql('ALTER TABLE sylius_currency ADD exchange_rate NUMERIC(10, 5) NOT NULL, ADD enabled TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE sylius_order ADD exchange_rate NUMERIC(10, 5) NOT NULL, DROP customer_ip, CHANGE checkout_completed_at completed_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_payment_method DROP position');
        $this->addSql('ALTER TABLE sylius_product ADD shipping_category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_product ADD CONSTRAINT FK_677B9B749E2D1A41 FOREIGN KEY (shipping_category_id) REFERENCES sylius_shipping_category (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_677B9B749E2D1A41 ON sylius_product (shipping_category_id)');
        $this->addSql('DROP INDEX product_association_idx ON sylius_product_association');
        $this->addSql('ALTER TABLE sylius_product_attribute DROP position');
        $this->addSql('ALTER TABLE sylius_product_variant DROP FOREIGN KEY FK_A29B5239E2D1A41');
        $this->addSql('DROP INDEX IDX_A29B5239E2D1A41 ON sylius_product_variant');
        $this->addSql('ALTER TABLE sylius_product_variant ADD price INT NOT NULL, ADD pricing_calculator VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD pricing_configuration LONGTEXT NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\', CHANGE shipping_category_id original_price INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sylius_province CHANGE country_id country_id INT DEFAULT NULL');
        $this->addSql('DROP INDEX slug_uidx ON sylius_taxon_translation');
        $this->addSql('ALTER TABLE sylius_taxon_translation ADD permalink VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('CREATE UNIQUE INDEX permalink_uidx ON sylius_taxon_translation (locale, permalink)');
        $this->addSql('ALTER TABLE toro_cms_page DROP viewers');
        $this->addSql('ALTER TABLE toro_cms_post DROP likeTotal, DROP dislikeTotal, DROP viewers');
    }
}
