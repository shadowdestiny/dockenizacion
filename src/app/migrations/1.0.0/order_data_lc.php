<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

class OrderDataLcMigration_100 extends Migration
{

    public function up()
    {
        $this->morphTable(
            'order_data_lc',
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
                    'order_data_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'id'
                    )
                ),
                new Column(
                    'order_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'order_data_id'
                    )
                ),
                new Column(
                    'add_date',
                    array(
                        'type' => Column::TYPE_DATETIME,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'order_id'
                    )
                ),
                new Column(
                    'draw_date',
                    array(
                        'type' => Column::TYPE_DATE,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'add_date'
                    )
                ),
                new Column(
                    'draw_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 11,
                        'after' => 'draw_date'
                    )
                ),
                new Column(
                    'abo',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 3,
                        'after' => 'draw_id'
                    )
                ),
                new Column(
                    'tuesday',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 3,
                        'after' => 'abo'
                    )
                ),
                new Column(
                    'friday',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 3,
                        'after' => 'tuesday'
                    )
                ),
                new Column(
                    'num_draws',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 3,
                        'after' => 'friday'
                    )
                ),
                new Column(
                    'numbers',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 255,
                        'after' => 'num_draws'
                    )
                ),
                new Column(
                    'stars',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 255,
                        'after' => 'numbers'
                    )
                ),
                new Column(
                    'state',
                    array(
                        'type' => Column::TYPE_CHAR,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'stars'
                    )
                )
            ),
            'indexes' => array(
                new Index('PRIMARY', array('id')),
                new Index('order_id', array('order_id')),
                new Index('state', array('state'))
            ),
            'options' => array(
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '7',
                'ENGINE' => 'MyISAM',
                'TABLE_COLLATION' => 'utf8_unicode_ci'
            )
        )
        );
    }
}
