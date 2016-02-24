<?php namespace EuroMillions\web\migrations;

use Phinx\Migration\AbstractMigration;

class SingleBetPrice extends AbstractMigration
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
        $this->execute("
                ALTER TABLE lotteries ADD single_bet_price_amount BIGINT NOT NULL, ADD single_bet_price_currency_name VARCHAR(255) NOT NULL;
                ALTER TABLE play_configs CHANGE draw_days draw_days INT DEFAULT NULL;
        ");
    }
}
