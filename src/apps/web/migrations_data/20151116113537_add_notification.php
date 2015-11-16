<?php

use Phinx\Migration\AbstractMigration;

class AddNotification extends AbstractMigration
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
        $sql = "INSERT INTO notifications (`description`) VALUES ('When Jackpot reach');
                INSERT INTO notifications (`description`) VALUES ('When Auto-Play has not enough funds');
                INSERT INTO notifications (`description`) VALUES ('When Auto-Play has played the last Draw');
                INSERT INTO notifications (`description`) VALUES ('Results of the Draw');";
        $this->execute($sql);
    }
}
