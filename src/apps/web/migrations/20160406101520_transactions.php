<?php

use Phinx\Migration\AbstractMigration;

class Transactions extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $this->execute('CREATE TABLE transactions (id INT UNSIGNED AUTO_INCREMENT NOT NULL, user_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', date DATE DEFAULT NULL, wallet_before_uploaded_amount BIGINT DEFAULT NULL, wallet_before_uploaded_currency_name VARCHAR(255) DEFAULT NULL, wallet_before_winnings_amount BIGINT DEFAULT NULL, wallet_before_winnings_currency_name VARCHAR(255) DEFAULT NULL, wallet_after_uploaded_amount BIGINT DEFAULT NULL, wallet_after_uploaded_currency_name VARCHAR(255) DEFAULT NULL, wallet_after_winnings_amount BIGINT DEFAULT NULL, wallet_after_winnings_currency_name VARCHAR(255) DEFAULT NULL, entity_type VARCHAR(255) DEFAULT NULL, entity_data VARCHAR(255) DEFAULT NULL, INDEX IDX_EAA81A4CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
                        ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4CA76ED395 FOREIGN KEY (user_id) REFERENCES users (id);
        ');
    }
}
