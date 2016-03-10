<?php

use Phinx\Migration\AbstractMigration;

class EuroMillionsDrawBreakDown extends AbstractMigration
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
        $this->execute("ALTER TABLE euromillions_draws
                        ADD break_down_category_one_name VARCHAR(255) DEFAULT NULL,
                        ADD break_down_category_one_lottery_prizes VARCHAR(255) DEFAULT NULL,
                        ADD break_down_category_one_winners VARCHAR(255) DEFAULT NULL,
                        ADD break_down_category_two_name VARCHAR(255) DEFAULT NULL,
                        ADD break_down_category_two_lottery_prizes VARCHAR(255) DEFAULT NULL,
                        ADD break_down_category_two_winners VARCHAR(255) DEFAULT NULL,
                        ADD break_down_category_three_name VARCHAR(255) DEFAULT NULL,
                        ADD break_down_category_three_lottery_prizes VARCHAR(255) DEFAULT NULL,
                        ADD break_down_category_three_winners VARCHAR(255) DEFAULT NULL,
                        ADD break_down_category_four_name VARCHAR(255) DEFAULT NULL,
                        ADD break_down_category_four_lottery_prizes VARCHAR(255) DEFAULT NULL,
                        ADD break_down_category_four_winners VARCHAR(255) DEFAULT NULL,
                        ADD break_down_category_five_name VARCHAR(255) DEFAULT NULL,
                        ADD break_down_category_five_lottery_prizes VARCHAR(255) DEFAULT NULL,
                        ADD break_down_category_five_winners VARCHAR(255) DEFAULT NULL,
                        ADD break_down_category_six_name VARCHAR(255) DEFAULT NULL,
                        ADD break_down_category_six_lottery_prizes VARCHAR(255) DEFAULT NULL,
                        ADD break_down_category_six_winners VARCHAR(255) DEFAULT NULL,
                        ADD break_down_category_seven_name VARCHAR(255) DEFAULT NULL,
                        ADD break_down_category_seven_lottery_prizes VARCHAR(255) DEFAULT NULL,
                        ADD break_down_category_seven_winners VARCHAR(255) DEFAULT NULL,
                        ADD break_down_category_eight_name VARCHAR(255) DEFAULT NULL,
                        ADD break_down_category_eight_lottery_prizes VARCHAR(255) DEFAULT NULL,
                        ADD break_down_category_eight_winners VARCHAR(255) DEFAULT NULL,
                        ADD break_down_category_nine_name VARCHAR(255) DEFAULT NULL,
                        ADD break_down_category_nine_lottery_prizes VARCHAR(255) DEFAULT NULL,
                        ADD break_down_category_nine_winners VARCHAR(255) DEFAULT NULL,
                        ADD break_down_category_ten_name VARCHAR(255) DEFAULT NULL,
                        ADD break_down_category_ten_lottery_prizes VARCHAR(255) DEFAULT NULL,
                        ADD break_down_category_ten_winners VARCHAR(255) DEFAULT NULL,
                        ADD break_down_category_eleven_name VARCHAR(255) DEFAULT NULL,
                        ADD break_down_category_eleven_lottery_prizes VARCHAR(255) DEFAULT NULL,
                        ADD break_down_category_eleven_winners VARCHAR(255) DEFAULT NULL,
                        ADD break_down_category_twelve_name VARCHAR(255) DEFAULT NULL,
                        ADD break_down_category_twelve_lottery_prizes VARCHAR(255) DEFAULT NULL,
                        ADD break_down_category_twelve_winners VARCHAR(255) DEFAULT NULL,
                        ADD break_down_category_thirteen_name VARCHAR(255) DEFAULT NULL,
                        ADD break_down_category_thirteen_lottery_prizes VARCHAR(255) DEFAULT NULL,
                        ADD break_down_category_thirteen_winners VARCHAR(255) DEFAULT NULL;");
    }
}