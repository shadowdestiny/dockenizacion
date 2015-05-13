<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

class CartsMigration_100 extends Migration
{

    public function up()
    {
        $this->morphTable(
            'carts',
            array(
            'columns' => array(
                new Column(
                    'cart_id',
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
                    'user_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'cart_id'
                    )
                ),
                new Column(
                    'guest_id',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 32,
                        'after' => 'user_id'
                    )
                ),
                new Column(
                    'customer_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'guest_id'
                    )
                ),
                new Column(
                    'state',
                    array(
                        'type' => Column::TYPE_CHAR,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'customer_id'
                    )
                ),
                new Column(
                    'creation_date',
                    array(
                        'type' => Column::TYPE_DATETIME,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'state'
                    )
                ),
                new Column(
                    'last_update',
                    array(
                        'type' => Column::TYPE_DATETIME,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'creation_date'
                    )
                ),
                new Column(
                    'total_price',
                    array(
                        'type' => Column::TYPE_DECIMAL,
                        'notNull' => true,
                        'size' => 10,
                        'scale' => 2,
                        'after' => 'last_update'
                    )
                )
            ),
            'indexes' => array(
                new Index('PRIMARY', array('cart_id')),
                new Index('user_id', array('user_id', 'guest_id')),
                new Index('guest_id', array('guest_id')),
                new Index('last_update', array('last_update')),
                new Index('customer_id', array('customer_id'))
            ),
            'options' => array(
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '21',
                'ENGINE' => 'InnoDB',
                'TABLE_COLLATION' => 'utf8_unicode_ci'
            )
        )
        );
    }
}
