<?php

use Phinx\Migration\AbstractMigration;

class UserAdmin extends AbstractMigration
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
        $this->execute('CREATE TABLE users_admin (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, surname VARCHAR(30) NOT NULL, email VARCHAR(60) NOT NULL, password VARCHAR(30) NOT NULL, useraccess VARCHAR(15) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;');
    }
}
