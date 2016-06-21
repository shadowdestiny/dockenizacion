<?php

use Phinx\Migration\AbstractMigration;

class MatchDateNullable extends AbstractMigration
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
        $this->execute('ALTER TABLE matcher CHANGE matchDate matchDate DATETIME DEFAULT NULL;
                        ALTER TABLE matcher ADD CONSTRAINT FK_7121252C68D879E3 FOREIGN KEY (providerBetId) REFERENCES log_validation_api (id_ticket);
                        ALTER TABLE matcher ADD CONSTRAINT FK_7121252C64B64DCC FOREIGN KEY (userId) REFERENCES users (id);
                      ');
    }
}
