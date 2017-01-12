<?php

use Phinx\Migration\AbstractMigration;

class SubscriptionInWallet extends AbstractMigration
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
        $this->execute('ALTER TABLE users ADD wallet_subscription_amount BIGINT DEFAULT 0, ADD wallet_subscription_currency_name VARCHAR(255) DEFAULT \'EUR\';
                        ALTER TABLE transactions ADD wallet_before_subscription_amount BIGINT DEFAULT 0, ADD wallet_before_subscription_currency_name VARCHAR(255) DEFAULT \'EUR\', ADD wallet_after_subscription_amount BIGINT DEFAULT 0, ADD wallet_after_subscription_currency_name VARCHAR(255) DEFAULT \'EUR\';
        ');
    }
}
