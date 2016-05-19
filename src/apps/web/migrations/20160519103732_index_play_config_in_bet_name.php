<?php

use Phinx\Migration\AbstractMigration;

class IndexPlayConfigInBetName extends AbstractMigration
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
        $this->execute('ALTER TABLE bets DROP FOREIGN KEY FK_7C28752B13C82ADE;
                        DROP INDEX IDX_7C28752B13C82ADE ON bets;
                        ALTER TABLE bets CHANGE play_config_id playConfig_id INT UNSIGNED DEFAULT NULL;
                        ALTER TABLE bets ADD CONSTRAINT FK_7C28752B43FD6CAB FOREIGN KEY (playConfig_id) REFERENCES play_configs (id);
                        CREATE INDEX IDX_7C28752B43FD6CAB ON bets (playConfig_id);');
    }
}
