<?php

use Phinx\Migration\AbstractMigration;

class NotNullEuroMillionDrawInBet extends AbstractMigration
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
        $this->execute('ALTER TABLE bets DROP INDEX FK_7C28752BB6F88F5C, ADD UNIQUE INDEX UNIQ_7C28752BC9AECF8 (euromillions_draw_id);');
    }
}
