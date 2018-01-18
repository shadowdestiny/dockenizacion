<?php

use Phinx\Migration\AbstractMigration;

class ChristmasAwards extends AbstractMigration
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
        //$this->execute('CREATE TABLE christmas_awards (id INT UNSIGNED AUTO_INCREMENT NOT NULL, number VARCHAR(5) NOT NULL, christmas_ticket_id INT NOT NULL, prize INT NOT NULL, UNIQUE INDEX UNIQ_E2A6793896901F54 (number), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;');
    }
}
