<?php

use Phinx\Migration\AbstractMigration;

class NewCategoriesInEuromillionsBreakDown extends AbstractMigration
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
        $this->execute('ALTER TABLE euromillions_draws ADD break_down_category_fourteen_name VARCHAR(255) DEFAULT NULL, ADD break_down_category_fourteen_winners VARCHAR(255) DEFAULT NULL, ADD break_down_category_fourteen_lottery_prize_amount BIGINT DEFAULT NULL, ADD break_down_category_fourteen_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL, ADD break_down_category_fifteen_name VARCHAR(255) DEFAULT NULL, ADD break_down_category_fifteen_winners VARCHAR(255) DEFAULT NULL, ADD break_down_category_fifteen_lottery_prize_amount BIGINT DEFAULT NULL, ADD break_down_category_fifteen_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL, ADD break_down_category_sixteen_name VARCHAR(255) DEFAULT NULL, ADD break_down_category_sixteen_winners VARCHAR(255) DEFAULT NULL, ADD break_down_category_sixteen_lottery_prize_amount BIGINT DEFAULT NULL, ADD break_down_category_sixteen_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL, ADD break_down_category_seventeen_name VARCHAR(255) DEFAULT NULL, ADD break_down_category_seventeen_winners VARCHAR(255) DEFAULT NULL, ADD break_down_category_seventeen_lottery_prize_amount BIGINT DEFAULT NULL, ADD break_down_category_seventeen_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL;');
    }
}
