<?php

use Phinx\Migration\AbstractMigration;

class FeeValuesSiteConfig extends AbstractMigration
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
        $sql = "INSERT INTO site_config (`name`,`value`,`description`) VALUES ('fee','35','Fee charge when total cart is below fee limit');
                INSERT INTO site_config (`name`,`value`,`description`) VALUES ('fee_to_limit','1200','Fee limit to charge');";

        $this->execute($sql);
    }
}
