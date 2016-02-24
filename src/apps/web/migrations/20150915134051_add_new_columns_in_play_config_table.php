<?php

use Phinx\Migration\AbstractMigration;

class AddNewColumnsInPlayConfigTable extends AbstractMigration
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
                ALTER TABLE bets DROP FOREIGN KEY FK_7C28752BC9AECF8;
                DROP INDEX UNIQ_7C28752BB6F88F5C ON bets;
                ALTER TABLE play_configs ADD draw_days INT NOT NULL, ADD last_draw_date DATE DEFAULT NULL, CHANGE play_config_date_end start_draw_date DATE DEFAULT NULL;
        ");
    }
}
