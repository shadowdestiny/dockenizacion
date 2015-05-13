<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

class TicketTypesMigration_100 extends Migration
{

    public function up()
    {
        $this->morphTable(
            'ticket_types',
            array(
            'columns' => array(
                new Column(
                    'ticket_type_id',
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
                    'active',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 3,
                        'after' => 'ticket_type_id'
                    )
                ),
                new Column(
                    'name',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 255,
                        'after' => 'active'
                    )
                ),
                new Column(
                    'numbers',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 3,
                        'after' => 'name'
                    )
                ),
                new Column(
                    'stars',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 3,
                        'after' => 'numbers'
                    )
                ),
                new Column(
                    'price',
                    array(
                        'type' => Column::TYPE_DECIMAL,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'scale' => 2,
                        'after' => 'stars'
                    )
                )
            ),
            'indexes' => array(
                new Index('PRIMARY', array('ticket_type_id')),
                new Index('numbers', array('numbers', 'stars')),
                new Index('active', array('active'))
            ),
            'options' => array(
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '25',
                'ENGINE' => 'MyISAM',
                'TABLE_COLLATION' => 'utf8_unicode_ci'
            )
        )
        );
    }
}
