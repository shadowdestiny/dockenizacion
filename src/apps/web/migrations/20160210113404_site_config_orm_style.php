<?php

use Phinx\Migration\AbstractMigration;

class SiteConfigOrmStyle extends AbstractMigration
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
        $this->execute('drop table site_config');
        $this->execute('CREATE TABLE site_config (id INT UNSIGNED AUTO_INCREMENT NOT NULL, fee VARCHAR(255) DEFAULT NULL, fee_to_limit VARCHAR(255) DEFAULT NULL, default_currency_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;');
    }
}
