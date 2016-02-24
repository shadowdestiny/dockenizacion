<?php namespace EuroMillions\web\migrations;

use Phinx\Migration\AbstractMigration;

class Wallet extends AbstractMigration
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
        $this->execute('ALTER TABLE users ADD wallet_winnings_amount BIGINT DEFAULT NULL, ADD wallet_winnings_currency_name VARCHAR(255) DEFAULT "EUR", CHANGE balance_amount wallet_uploaded_amount BIGINT DEFAULT 0, CHANGE balance_currency_name wallet_uploaded_currency_name VARCHAR(255) DEFAULT "EUR";');
    }
}
