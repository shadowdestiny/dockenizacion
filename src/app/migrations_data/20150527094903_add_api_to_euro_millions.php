<?php

use Phinx\Migration\AbstractMigration;

class AddApiToEuroMillions extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * Uncomment this method if you would like to use it.
     *
    public function change()
    {
    }
    */
    
    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->execute("UPDATE lotteries SET api = 'LotteryDotIe' WHERE name='EuroMillions'");
    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }
}