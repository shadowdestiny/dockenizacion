<?php

use Phinx\Migration\AbstractMigration;

class AddLotteryRelationOnPlayConfig extends AbstractMigration
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
        $this->execute('ALTER TABLE play_configs ADD lottery_id INT UNSIGNED DEFAULT NULL;
                        ALTER TABLE play_configs ADD CONSTRAINT FK_34771F83CFAA77DD FOREIGN KEY (lottery_id) REFERENCES lotteries (id);
                        CREATE UNIQUE INDEX UNIQ_34771F83CFAA77DD ON play_configs (lottery_id);
                      ');
    }
}
