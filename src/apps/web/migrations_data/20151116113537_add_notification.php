<?php namespace EuroMillions\web\migrations_data;

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
        $sql = "INSERT INTO notifications (`description`,`notification_type`) VALUES ('When Jackpot reach',1);
                INSERT INTO notifications (`description`,`notification_type`) VALUES ('When Auto-Play has not enough funds',2);
                INSERT INTO notifications (`description`,`notification_type`) VALUES ('When Auto-Play has played the last Draw',3);
                INSERT INTO notifications (`description`,`notification_type`) VALUES ('Results of the Draw',4);";
        $this->execute($sql);
    }
}
