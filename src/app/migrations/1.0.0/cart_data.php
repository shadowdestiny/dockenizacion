<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

class CartDataMigration_100 extends Migration
{

    public function up()
    {
        $this->morphTable(
            'cart_data',
            array(
            'columns' => array(
                new Column(
                    'cart_data_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'autoIncrement' => true,
                        'size' => 11,
                        'first' => true
                    )
                ),
                new Column(
                    'cart_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'cart_data_id'
                    )
                ),
                new Column(
                    'lottery',
                    array(
                        'type' => Column::TYPE_CHAR,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'cart_id'
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
                    'start_draw_date',
                    array(
                        'type' => Column::TYPE_DATETIME,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'add_date'
                    )
                ),
                new Column(
                    'price_total',
                    array(
                        'type' => Column::TYPE_DECIMAL,
                        'notNull' => true,
                        'size' => 10,
                        'scale' => 2,
                        'after' => 'start_draw_date'
                    )
                )
            ),
            'indexes' => array(
                new Index('PRIMARY', array('cart_data_id')),
                new Index('cart_id', array('cart_id')),
                new Index('lottery', array('lottery')),
                new Index('post_id', array('post_id')),
                new Index('add_date', array('add_date')),
                new Index('draw_date', array('start_draw_date')),
                new Index('cart_id_2', array('cart_id', 'lottery', 'post_id'))
            ),
            'options' => array(
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '13',
                'ENGINE' => 'InnoDB',
                'TABLE_COLLATION' => 'utf8_unicode_ci'
            )
        )
        );
    }
}
