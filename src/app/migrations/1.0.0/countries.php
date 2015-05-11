<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

class CountriesMigration_100 extends Migration
{

    public function up()
    {
        $this->morphTable(
            'countries',
            array(
            'columns' => array(
                new Column(
                    'country_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'autoIncrement' => true,
                        'size' => 10,
                        'first' => true
                    )
                ),
                new Column(
                    'active_registration',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 3,
                        'after' => 'country_id'
                    )
                ),
                new Column(
                    'active_payout',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 3,
                        'after' => 'active_registration'
                    )
                ),
                new Column(
                    'short_code',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 6,
                        'after' => 'active_payout'
                    )
                ),
                new Column(
                    'name',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 255,
                        'after' => 'short_code'
                    )
                ),
                new Column(
                    'iban_mandatory',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'name'
                    )
                ),
                new Column(
                    'iban_example',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 64,
                        'after' => 'iban_mandatory'
                    )
                )
            ),
            'indexes' => array(
                new Index('PRIMARY', array('country_id')),
                new Index('short_code', array('short_code')),
                new Index('name', array('name')),
                new Index('active_registration', array('active_registration')),
                new Index('active_payout', array('active_payout'))
            ),
            'options' => array(
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '251',
                'ENGINE' => 'MyISAM',
                'TABLE_COLLATION' => 'utf8_unicode_ci'
            )
        )
        );
    }
}
