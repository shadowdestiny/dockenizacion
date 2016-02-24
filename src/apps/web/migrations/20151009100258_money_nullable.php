<?php

use Phinx\Migration\AbstractMigration;

class MoneyNullable extends AbstractMigration
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
                ALTER TABLE euromillions_draws CHANGE break_down_category_one_lottery_prize_amount break_down_category_one_lottery_prize_amount BIGINT DEFAULT NULL,
                CHANGE break_down_category_one_lottery_prize_currency_name break_down_category_one_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL,
                CHANGE break_down_category_two_lottery_prize_amount break_down_category_two_lottery_prize_amount BIGINT DEFAULT NULL,
                CHANGE break_down_category_two_lottery_prize_currency_name break_down_category_two_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL,
                CHANGE break_down_category_three_lottery_prize_amount break_down_category_three_lottery_prize_amount BIGINT DEFAULT NULL,
                CHANGE break_down_category_three_lottery_prize_currency_name break_down_category_three_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL,
                CHANGE break_down_category_four_lottery_prize_amount break_down_category_four_lottery_prize_amount BIGINT DEFAULT NULL,
                CHANGE break_down_category_four_lottery_prize_currency_name break_down_category_four_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL,
                CHANGE break_down_category_five_lottery_prize_amount break_down_category_five_lottery_prize_amount BIGINT DEFAULT NULL,
                CHANGE break_down_category_five_lottery_prize_currency_name break_down_category_five_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL,
                CHANGE break_down_category_six_lottery_prize_amount break_down_category_six_lottery_prize_amount BIGINT DEFAULT NULL,
                CHANGE break_down_category_six_lottery_prize_currency_name break_down_category_six_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL,
                CHANGE break_down_category_seven_lottery_prize_amount break_down_category_seven_lottery_prize_amount BIGINT DEFAULT NULL,
                CHANGE break_down_category_seven_lottery_prize_currency_name break_down_category_seven_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL,
                CHANGE break_down_category_eight_lottery_prize_amount break_down_category_eight_lottery_prize_amount BIGINT DEFAULT NULL,
                CHANGE break_down_category_eight_lottery_prize_currency_name break_down_category_eight_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL,
                CHANGE break_down_category_nine_lottery_prize_amount break_down_category_nine_lottery_prize_amount BIGINT DEFAULT NULL,
                CHANGE break_down_category_nine_lottery_prize_currency_name break_down_category_nine_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL,
                 CHANGE break_down_category_ten_lottery_prize_amount break_down_category_ten_lottery_prize_amount BIGINT DEFAULT NULL,
                 CHANGE break_down_category_ten_lottery_prize_currency_name break_down_category_ten_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL,
                 CHANGE break_down_category_eleven_lottery_prize_amount break_down_category_eleven_lottery_prize_amount BIGINT DEFAULT NULL,
                 CHANGE break_down_category_eleven_lottery_prize_currency_name break_down_category_eleven_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL,
                 CHANGE break_down_category_twelve_lottery_prize_amount break_down_category_twelve_lottery_prize_amount BIGINT DEFAULT NULL,
                 CHANGE break_down_category_twelve_lottery_prize_currency_name break_down_category_twelve_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL,
                 CHANGE break_down_category_thirteen_lottery_prize_amount break_down_category_thirteen_lottery_prize_amount BIGINT DEFAULT NULL,
                 CHANGE break_down_category_thirteen_lottery_prize_currency_name break_down_category_thirteen_lottery_prize_currency_name VARCHAR(255) DEFAULT NULL;');

    }
}
