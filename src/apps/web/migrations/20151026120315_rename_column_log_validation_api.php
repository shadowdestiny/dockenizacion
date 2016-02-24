<?php namespace EuroMillions\web\migrations;

use Phinx\Migration\AbstractMigration;

class RenameColumnLogValidationApi extends AbstractMigration
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
            $this->execute('DROP INDEX IDX_4B44F776754B05EC ON log_validation_api;
                            ALTER TABLE log_validation_api ADD id_ticket INT NOT NULL, DROP castillo_bet_id;
            ');
    }
}
