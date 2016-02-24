<?php namespace EuroMillions\web\migrations;

use Phinx\Migration\AbstractMigration;

class MoneyNull extends AbstractMigration
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
        $this->execute('ALTER TABLE lotteries CHANGE single_bet_price_amount single_bet_price_amount BIGINT DEFAULT NULL, CHANGE single_bet_price_currency_name single_bet_price_currency_name VARCHAR(255) DEFAULT NULL;
                        ALTER TABLE euromillions_draws CHANGE jackpot_amount jackpot_amount BIGINT DEFAULT NULL, CHANGE jackpot_currency_name jackpot_currency_name VARCHAR(255) DEFAULT NULL;
                        ALTER TABLE users CHANGE balance_amount balance_amount BIGINT DEFAULT NULL, CHANGE balance_currency_name balance_currency_name VARCHAR(255) DEFAULT NULL;
                      ');
    }
}
