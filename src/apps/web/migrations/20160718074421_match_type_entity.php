<?php

use Phinx\Migration\AbstractMigration;

class MatchTypeEntity extends AbstractMigration
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
        $this->execute('CREATE TABLE matchType (id INT UNSIGNED AUTO_INCREMENT NOT NULL, matchName VARCHAR(20) DEFAULT NULL, lottery VARCHAR(10) DEFAULT NULL, transactionType VARCHAR(8) DEFAULT NULL, leftEdge VARCHAR(10) DEFAULT NULL, rightEdge VARCHAR(10) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;');
    }
}
