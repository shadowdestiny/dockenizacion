<?php namespace EuroMillions\web\migrations;

use Phinx\Migration\AbstractMigration;

class RenameColumnJoinEuroMillionsDrawInBetTable extends AbstractMigration
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
                ALTER TABLE bets CHANGE euromillions_draw euromillions_draw_id INT UNSIGNED DEFAULT NULL;
                ALTER TABLE bets ADD CONSTRAINT FK_7C28752BC9AECF8 FOREIGN KEY (euromillions_draw_id) REFERENCES euromillions_draws (id);
                CREATE UNIQUE INDEX UNIQ_7C28752BC9AECF8 ON bets (euromillions_draw_id);
        ");
    }
}
