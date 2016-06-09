<?php

use Phinx\Migration\AbstractMigration;

class PlayConfigInLogValidationApi extends AbstractMigration
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
        $this->execute('ALTER TABLE log_validation_api ADD playConfig_id INT UNSIGNED DEFAULT NULL;
                        ALTER TABLE log_validation_api ADD CONSTRAINT FK_4B44F77643FD6CAB FOREIGN KEY (playConfig_id) REFERENCES play_configs (id);
                        CREATE INDEX IDX_4B44F77643FD6CAB ON log_validation_api (playConfig_id);
        ');
    }
}
