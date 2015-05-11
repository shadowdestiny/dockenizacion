<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

class OrderDataMigration_100 extends Migration
{

    public function up()
    {
        $this->morphTable(
            'order_data',
            array(
            'columns' => array(
                new Column(
                    'order_data_id',
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
                    'lottery',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 32,
                        'after' => 'order_id'
                    )
                ),
                new Column(
                    'post_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'lottery'
                    )
                ),
                new Column(
                    'add_date',
                    array(
                        'type' => Column::TYPE_DATETIME,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'post_id'
                    )
                ),
                new Column(
                    'product_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'add_date'
                    )
                ),
                new Column(
                    'start_draw_date',
                    array(
                        'type' => Column::TYPE_DATETIME,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'product_id'
                    )
                ),
                new Column(
                    'price',
                    array(
                        'type' => Column::TYPE_DECIMAL,
                        'notNull' => true,
                        'size' => 10,
                        'scale' => 2,
                        'after' => 'start_draw_date'
                    )
                ),
                new Column(
                    'state',
                    array(
                        'type' => Column::TYPE_CHAR,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'price'
                    )
                )
            ),
            'indexes' => array(
                new Index('PRIMARY', array('order_data_id'))
            ),
            'options' => array(
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '10',
                'ENGINE' => 'MyISAM',
                'TABLE_COLLATION' => 'utf8_unicode_ci'
            )
        )
        );
    }
}
