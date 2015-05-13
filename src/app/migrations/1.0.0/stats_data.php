<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

class StatsDataMigration_100 extends Migration
{

    public function up()
    {
        $this->morphTable(
            'stats_data',
            array(
            'columns' => array(
                new Column(
                    'year',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 8,
                        'first' => true
                    )
                ),
                new Column(
                    'month',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 3,
                        'after' => 'year'
                    )
                ),
                new Column(
                    'day',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 3,
                        'after' => 'month'
                    )
                ),
                new Column(
                    'hour',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 3,
                        'after' => 'day'
                    )
                ),
                new Column(
                    'stats_type_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 8,
                        'after' => 'hour'
                    )
                ),
                new Column(
                    'customer_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 8,
                        'after' => 'stats_type_id'
                    )
                ),
                new Column(
                    'value',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'customer_id'
                    )
                )
            ),
            'indexes' => array(
                new Index('PRIMARY', array('year', 'month', 'day', 'hour', 'stats_type_id', 'customer_id'))
            ),
            'options' => array(
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '',
                'ENGINE' => 'InnoDB',
                'TABLE_COLLATION' => 'utf8_unicode_ci'
            )
        )
        );
    }
}
