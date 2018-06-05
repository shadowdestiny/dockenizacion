<?php

use Phinx\Migration\AbstractMigration;

class AddMessageToTransactions extends AbstractMigration
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
        $this->execute('ALTER TABLE transactions ADD message VARCHAR(255) DEFAULT NULL, CHANGE wallet_before_subscription_amount wallet_before_subscription_amount BIGINT DEFAULT NULL, CHANGE wallet_before_subscription_currency_name wallet_before_subscription_currency_name VARCHAR(255) DEFAULT NULL, CHANGE wallet_after_subscription_amount wallet_after_subscription_amount BIGINT DEFAULT NULL, CHANGE wallet_after_subscription_currency_name wallet_after_subscription_currency_name VARCHAR(255) DEFAULT NULL;');
    }
}
