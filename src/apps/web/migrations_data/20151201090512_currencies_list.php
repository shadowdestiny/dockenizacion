<?php

use Phinx\Migration\AbstractMigration;

class CurrenciesList extends AbstractMigration
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
        $sql = "INSERT INTO currencies (`code`,`name`,`order`) VALUES ('EUR','Euro',1);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('USD','Us Dollar',2);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('COP','Colombian Peso',7);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('GBP','Pound Sterling',3);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('RUB','Russian Ruble',5);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('CHF','Swiss Franc',4);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('AUD','Australian Dolar',6);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('RON','Romanian Leu',8);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('BGN','Bulgarian Lev',9);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('ZAR','South African Rand',10);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('SEK','Swedish Krone',11);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('DKK','Danish Krone',12);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('INR','Indian Rupee',13);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('BLR','Belarusian Ruble',14);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('CAD','Canadian Dollar',15);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('CNY','Chinese Yuan',16);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('JPY','Japanese Yen',17);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('THB','Thai Baht',18);";
        $this->execute($sql);
    }
}
