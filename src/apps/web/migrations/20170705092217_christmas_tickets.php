<?php

use Phinx\Migration\AbstractMigration;

class ChristmasTickets extends AbstractMigration
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
        $this->execute('CREATE TABLE christmas_tickets (id INT UNSIGNED AUTO_INCREMENT NOT NULL, number VARCHAR(5) NOT NULL, n_series INT NOT NULL, serie_init INT NOT NULL, serie_end INT NOT NULL, n_fractions INT NOT NULL, fraction_init INT NOT NULL, fraction_end INT NOT NULL, UNIQUE INDEX UNIQ_2686B6EB96901F54 (number), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;');
    }
}
