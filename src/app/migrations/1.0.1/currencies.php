<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

class CurrenciesMigration_101 extends Migration
{

    public function up()
    {
        $this->morphTable(
            'currencies',
            array(
            'columns' => array(
                new Column(
                    'id',
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
                    'currency',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 16,
                        'after' => 'id'
                    )
                ),
                new Column(
                    'active',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 3,
                        'after' => 'currency'
                    )
                ),
                new Column(
                    'default_locale',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'active'
                    )
                ),
                new Column(
                    'format_pattern',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 32,
                        'after' => 'default_locale'
                    )
                ),
                new Column(
                    'rate',
                    array(
                        'type' => Column::TYPE_DECIMAL,
                        'notNull' => true,
                        'size' => 12,
                        'scale' => 6,
                        'after' => 'format_pattern'
                    )
                ),
                new Column(
                    'last_update',
                    array(
                        'type' => Column::TYPE_DATETIME,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'rate'
                    )
                )
            ),
            'indexes' => array(
                new Index('PRIMARY', array('id')),
                new Index('currency', array('currency')),
                new Index('active', array('active'))
            ),
            'options' => array(
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '35',
                'ENGINE' => 'InnoDB',
                'TABLE_COLLATION' => 'utf8_unicode_ci'
            )
        )
        );
    }
}
