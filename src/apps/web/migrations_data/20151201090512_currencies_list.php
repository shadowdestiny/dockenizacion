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
        $sql = "INSERT INTO currencies (`symbol`,`code_name`,`name`) VALUES ('&euro;','EUR','Euro');
                INSERT INTO currencies (`symbol`,`code_name`,`name`) VALUES ('$','USD','Us Dollar');
                INSERT INTO currencies (`symbol`,`code_name`,`name`) VALUES ('COP','COP','Colombian Peso');
                INSERT INTO currencies (`symbol`,`code_name`,`name`) VALUES ('&pound;','GBP','Pound Sterling');
                INSERT INTO currencies (`symbol`,`code_name`,`name`) VALUES ('&#x20bd;','RUR','Russian Ruble');
                INSERT INTO currencies (`symbol`,`code_name`,`name`) VALUES ('&#x43;&#x48;&#x46;','CHF','Swiss Franc');
                INSERT INTO currencies (`symbol`,`code_name`,`name`) VALUES ('&#x41;&#x24;','AUD','Australian Dolar');
                INSERT INTO currencies (`symbol`,`code_name`,`name`) VALUES ('lei','RON','Romanian Leu');
                INSERT INTO currencies (`symbol`,`code_name`,`name`) VALUES ('&#1083;&#1074;','BGN','Bulgarian Lev');
                INSERT INTO currencies (`symbol`,`code_name`,`name`) VALUES ('&#82;','ZAR','South African Rand');
                INSERT INTO currencies (`symbol`,`code_name`,`name`) VALUES ('&#107;&#114;','SEK','Swedish Krone');
                INSERT INTO currencies (`symbol`,`code_name`,`name`) VALUES ('&#107;&#114;','DKK','Danish Krone');
                INSERT INTO currencies (`symbol`,`code_name`,`name`) VALUES ('&#8377;','INR','Indian Rupee');
                INSERT INTO currencies (`symbol`,`code_name`,`name`) VALUES ('&#x20bd;','BLR','Belarusian Ruble');
                INSERT INTO currencies (`symbol`,`code_name`,`name`) VALUES ('&#36;','CAD','Canadian Dollar');
                INSERT INTO currencies (`symbol`,`code_name`,`name`) VALUES ('&#165;','CNY','Chinese Yuan');
                INSERT INTO currencies (`symbol`,`code_name`,`name`) VALUES ('&#165;','JPY','Japanese Yen');
                INSERT INTO currencies (`symbol`,`code_name`,`name`) VALUES ('&#3647;','THB','Thai Baht');";
        $this->execute($sql);
    }
}
