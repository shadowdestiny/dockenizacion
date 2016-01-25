<?php

use Phinx\Migration\AbstractMigration;

class OrderCurrencies extends AbstractMigration
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
        $sql = "INSERT INTO currencies (`symbol`,`code_name`,`name`,`order`) VALUES ('&euro;','EUR','Euro',1);
                INSERT INTO currencies (`symbol`,`code_name`,`name`,`order`) VALUES ('$','USD','Us Dollar',2);
                INSERT INTO currencies (`symbol`,`code_name`,`name`,`order`) VALUES ('COP','COP','Colombian Peso',7);
                INSERT INTO currencies (`symbol`,`code_name`,`name`,`order`) VALUES ('&pound;','GBP','Pound Sterling',3);
                INSERT INTO currencies (`symbol`,`code_name`,`name`,`order`) VALUES ('&#x20bd;','RUB','Russian Ruble',5);
                INSERT INTO currencies (`symbol`,`code_name`,`name`,`order`) VALUES ('&#x43;&#x48;&#x46;','CHF','Swiss Franc',4);
                INSERT INTO currencies (`symbol`,`code_name`,`name`,`order`) VALUES ('&#x41;&#x24;','AUD','Australian Dolar',6);
                INSERT INTO currencies (`symbol`,`code_name`,`name`,`order`) VALUES ('lei','RON','Romanian Leu',8);
                INSERT INTO currencies (`symbol`,`code_name`,`name`,`order`) VALUES ('&#1083;&#1074;','BGN','Bulgarian Lev',9);
                INSERT INTO currencies (`symbol`,`code_name`,`name`,`order`) VALUES ('&#82;','ZAR','South African Rand',10);
                INSERT INTO currencies (`symbol`,`code_name`,`name`,`order`) VALUES ('&#107;&#114;','SEK','Swedish Krone',11);
                INSERT INTO currencies (`symbol`,`code_name`,`name`,`order`) VALUES ('&#107;&#114;','DKK','Danish Krone',12);
                INSERT INTO currencies (`symbol`,`code_name`,`name`,`order`) VALUES ('&#8377;','INR','Indian Rupee',13);
                INSERT INTO currencies (`symbol`,`code_name`,`name`,`order`) VALUES ('&#x20bd;','BLR','Belarusian Ruble',14);
                INSERT INTO currencies (`symbol`,`code_name`,`name`,`order`) VALUES ('&#36;','CAD','Canadian Dollar',15);
                INSERT INTO currencies (`symbol`,`code_name`,`name`,`order`) VALUES ('&#165;','CNY','Chinese Yuan',16);
                INSERT INTO currencies (`symbol`,`code_name`,`name`,`order`) VALUES ('&#165;','JPY','Japanese Yen',17);
                INSERT INTO currencies (`symbol`,`code_name`,`name`,`order`) VALUES ('&#3647;','THB','Thai Baht',18);";
        $this->execute($sql);
    }
}
