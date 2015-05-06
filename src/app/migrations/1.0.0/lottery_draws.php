<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

class LotteryDrawsMigration_100 extends Migration
{

    public function up()
    {
        $this->morphTable(
            'lottery_draws',
            array(
            'columns' => array(
                new Column(
                    'draw_id',
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
                    'draw_date',
                    array(
                        'type' => Column::TYPE_DATE,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'draw_id'
                    )
                ),
                new Column(
                    'jackpot',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 20,
                        'after' => 'draw_date'
                    )
                ),
                new Column(
                    'message',
                    array(
                        'type' => Column::TYPE_TEXT,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'jackpot'
                    )
                ),
                new Column(
                    'big_winner',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'message'
                    )
                ),
                new Column(
                    'published',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 3,
                        'after' => 'big_winner'
                    )
                )
            ),
            'indexes' => array(
                new Index('PRIMARY', array('draw_id')),
                new Index('draw_date', array('draw_date')),
                new Index('published', array('published'))
            ),
            'options' => array(
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '789',
                'ENGINE' => 'MyISAM',
                'TABLE_COLLATION' => 'utf8_unicode_ci'
            )
        )
        );
    }
}
