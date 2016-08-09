<?php

use Phinx\Migration\AbstractMigration;

class RenameKeyToTranlationKey extends AbstractMigration
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
        $this->execute('DROP INDEX UNIQ_C6B7DA878A90ABA9 ON translations;
                        ALTER TABLE translations CHANGE `key` translationKey VARCHAR(255) NOT NULL;
                        CREATE UNIQUE INDEX UNIQ_C6B7DA874836035C ON translations (translationKey);
        ');
    }
}
