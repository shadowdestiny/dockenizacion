<?php

use Phinx\Migration\AbstractMigration;

class PlayConfigToBetInLogValidationApi extends AbstractMigration
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
        $this->execute('ALTER TABLE log_validation_api DROP FOREIGN KEY FK_4B44F77643FD6CAB;
                        DROP INDEX IDX_4B44F77643FD6CAB ON log_validation_api;
                        ALTER TABLE log_validation_api CHANGE playconfig_id bet_id INT UNSIGNED DEFAULT NULL;
                        ALTER TABLE log_validation_api ADD CONSTRAINT FK_4B44F776D871DC26 FOREIGN KEY (bet_id) REFERENCES bets (id);
                        CREATE INDEX IDX_4B44F776D871DC26 ON log_validation_api (bet_id);
        ');
    }
}
