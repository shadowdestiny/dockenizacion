<?php

use Phinx\Migration\AbstractMigration;

class SiteConfigNameAsKey extends AbstractMigration
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
        $this->execute('ALTER TABLE site_config DROP PRIMARY KEY');
        $this->execute('ALTER TABLE site_config ADD id INT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE name name VARCHAR(255) DEFAULT NULL');
        $this->execute('ALTER TABLE site_config ADD PRIMARY KEY (id)');
    }
}
