<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

class StatsTicketsLcMigration_101 extends Migration
{

    public function up()
    {
        $this->morphTable(
            'stats_tickets_lc',
            array(
            'columns' => array(
                new Column(
                    'draw_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'first' => true
                    )
                ),
                new Column(
                    'customer_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'draw_id'
                    )
                ),
                new Column(
                    'product_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'customer_id'
                    )
                ),
                new Column(
                    'add_to_cart',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'product_id'
                    )
                ),
                new Column(
                    'remove_from_cart',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'add_to_cart'
                    )
                ),
                new Column(
                    'validation_error',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'remove_from_cart'
                    )
                ),
                new Column(
                    'validation_ok',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'validation_error'
                    )
                ),
                new Column(
                    'bought',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'validation_ok'
                    )
                ),
                new Column(
                    'bought_total_amount',
                    array(
                        'type' => Column::TYPE_DECIMAL,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'scale' => 2,
                        'after' => 'bought'
                    )
                ),
                new Column(
                    'won',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'bought_total_amount'
                    )
                ),
                new Column(
                    'won_total_amount',
                    array(
                        'type' => Column::TYPE_DECIMAL,
                        'notNull' => true,
                        'size' => 10,
                        'scale' => 2,
                        'after' => 'won'
                    )
                )
            ),
            'indexes' => array(
                new Index('PRIMARY', array('draw_id', 'customer_id', 'product_id'))
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
