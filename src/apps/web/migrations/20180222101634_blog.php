<?php

use Phinx\Migration\AbstractMigration;

class Blog extends AbstractMigration
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
        $this->execute('CREATE TABLE blog (id INT UNSIGNED AUTO_INCREMENT NOT NULL, url VARCHAR(100) NOT NULL, title VARCHAR(100) NOT NULL, description VARCHAR(200) NOT NULL, canonical VARCHAR(200) NOT NULL, language VARCHAR(2) NOT NULL, published TINYINT(1) NOT NULL, content LONGTEXT NOT NULL, image VARCHAR(100) NOT NULL, date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;');
    }
}
