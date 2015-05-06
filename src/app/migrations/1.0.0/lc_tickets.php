<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

class LcTicketsMigration_100 extends Migration
{

    public function up()
    {
        $this->morphTable(
            'lc_tickets',
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
                    'em_ticket_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'id'
                    )
                ),
                new Column(
                    'lc_ticket_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'em_ticket_id'
                    )
                ),
                new Column(
                    'customer_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'lc_ticket_id'
                    )
                ),
                new Column(
                    'user_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'customer_id'
                    )
                ),
                new Column(
                    'creation_date',
                    array(
                        'type' => Column::TYPE_DATETIME,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'user_id'
                    )
                ),
                new Column(
                    'draw_date',
                    array(
                        'type' => Column::TYPE_DATE,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'creation_date'
                    )
                ),
                new Column(
                    'number1',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 3,
                        'after' => 'draw_date'
                    )
                ),
                new Column(
                    'number2',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 3,
                        'after' => 'number1'
                    )
                ),
                new Column(
                    'number3',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 3,
                        'after' => 'number2'
                    )
                ),
                new Column(
                    'number4',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 3,
                        'after' => 'number3'
                    )
                ),
                new Column(
                    'number5',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 3,
                        'after' => 'number4'
                    )
                ),
                new Column(
                    'number6',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 3,
                        'after' => 'number5'
                    )
                ),
                new Column(
                    'number7',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 3,
                        'after' => 'number6'
                    )
                ),
                new Column(
                    'number8',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 3,
                        'after' => 'number7'
                    )
                ),
                new Column(
                    'number9',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 3,
                        'after' => 'number8'
                    )
                ),
                new Column(
                    'number10',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 3,
                        'after' => 'number9'
                    )
                ),
                new Column(
                    'star1',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 3,
                        'after' => 'number10'
                    )
                ),
                new Column(
                    'star2',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 3,
                        'after' => 'star1'
                    )
                ),
                new Column(
                    'star3',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 3,
                        'after' => 'star2'
                    )
                ),
                new Column(
                    'star4',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 3,
                        'after' => 'star3'
                    )
                ),
                new Column(
                    'star5',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 3,
                        'after' => 'star4'
                    )
                ),
                new Column(
                    'state',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 16,
                        'after' => 'star5'
                    )
                ),
                new Column(
                    'won',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'state'
                    )
                ),
                new Column(
                    'prize',
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
                new Index('PRIMARY', array('id')),
                new Index('em_ticket_id', array('em_ticket_id')),
                new Index('lc_ticket_id', array('lc_ticket_id')),
                new Index('user_id', array('user_id')),
                new Index('creation_date', array('creation_date')),
                new Index('draw_date', array('draw_date')),
                new Index('number1', array('number1')),
                new Index('number2', array('number2')),
                new Index('number3', array('number3')),
                new Index('number4', array('number4')),
                new Index('number5', array('number5')),
                new Index('number6', array('number6')),
                new Index('number7', array('number7')),
                new Index('number8', array('number8')),
                new Index('number9', array('number9')),
                new Index('number10', array('number10')),
                new Index('star1', array('star1')),
                new Index('star2', array('star2')),
                new Index('star3', array('star3')),
                new Index('star4', array('star4')),
                new Index('star5', array('star5')),
                new Index('state', array('state')),
                new Index('won', array('won'))
            ),
            'options' => array(
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '1',
                'ENGINE' => 'MyISAM',
                'TABLE_COLLATION' => 'utf8_unicode_ci'
            )
        )
        );
    }
}
