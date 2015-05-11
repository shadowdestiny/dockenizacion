<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

class LotteryWinnersMigration_101 extends Migration
{

    public function up()
    {
        $this->morphTable(
            'lottery_winners',
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
                    'draw_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'id'
                    )
                ),
                new Column(
                    'numbers',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 3,
                        'after' => 'draw_id'
                    )
                ),
                new Column(
                    'luckystars',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 3,
                        'after' => 'numbers'
                    )
                ),
                new Column(
                    'prize',
                    array(
                        'type' => Column::TYPE_DECIMAL,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 20,
                        'scale' => 2,
                        'after' => 'luckystars'
                    )
                ),
                new Column(
                    'winners',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 20,
                        'after' => 'prize'
                    )
                )
            ),
            'indexes' => array(
                new Index('PRIMARY', array('id')),
                new Index('draw_id', array('draw_id', 'numbers', 'luckystars')),
                new Index('draw_id_2', array('draw_id')),
                new Index('winners', array('winners'))
            ),
            'options' => array(
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '10426',
                'ENGINE' => 'InnoDB',
                'TABLE_COLLATION' => 'utf8_unicode_ci'
            )
        )
        );
    }
}
