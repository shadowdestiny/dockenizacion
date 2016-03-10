<?php

use Phinx\Migration\AbstractMigration;

class AddMissingCurrencies extends AbstractMigration
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
        $sql = "INSERT INTO currencies (`code`,`name`,`order`) VALUES ('UAH','Ukranian Hryvnia',19);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('HUF','Hungarian Forint',20);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('CZK','Czech koruna',21);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('PLN','Polish Zloty',22);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('LBP','Lebanese Pound',23);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('NOK','Norwegian Krone',24);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('MDL','Moldovan Leu',25);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('MXN','Mexican Peso',26);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('NZD','New Zealand Dollar',27);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('TRY','Turkish Lira',28);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('BRL','Brazilian Real',29);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('NGN','Nigerian Naira',30);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('AZN','Azerbaijani Manat',31);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('PHP','Phillippine Peso',32);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('KZT','Kazakhstani Tenge',33);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('ALL','Albanian Lek',34);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('RSD','Serbian Dinar',35);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('MKD','Macedonian Denar',36);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('KES','Kenyan Shilling',37);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('IDR','Indonesian Rupiah',38);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('ILS','Israeli Shekel',39);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('CLP','Chilean Peso',40);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('KRW','South Korean Won',41);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('SGD','Singapore Dollar',42);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('PKR','Pakistani Rupee',43);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('BAM','Bosnia and Herzegovina Convertible Mark',44);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('HKD','Hong Kong Dollar',45);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('GEL','Georgian Lari',46);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('QAR','Qatari Riyal',47);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('MYR','Malaysian Ringgit',48);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('ARS','Argentine Peso',49);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('PEN','Peruvian Sol',50);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('ISK','Icelandic Krona',51);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('BOB','Bolivian Boliviano',52);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('PYG','Paraguay Guarani',53);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('VEF','Venezuelan Bolívar',54);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('ILS','Israeli Shekel',55);
                INSERT INTO currencies (`code`,`name`,`order`) VALUES ('AED','Emirati Dirham',56);";
        $this->execute($sql);
    }

}
