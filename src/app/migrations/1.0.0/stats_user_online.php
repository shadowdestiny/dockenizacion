<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

class StatsUserOnlineMigration_100 extends Migration
{

    public function up()
    {
        $this->morphTable(
            'stats_user_online',
            array(
            'columns' => array(
                new Column(
                    'year',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 4,
                        'first' => true
                    )
                ),
                new Column(
                    'month',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 2,
                        'after' => 'year'
                    )
                ),
                new Column(
                    'day',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 2,
                        'after' => 'month'
                    )
                ),
                new Column(
                    'country',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 6,
                        'after' => 'day'
                    )
                ),
                new Column(
                    'gender_m',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'country'
                    )
                ),
                new Column(
                    'gender_f',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'gender_m'
                    )
                ),
                new Column(
                    'gender_n',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'gender_f'
                    )
                )
            ),
            'indexes' => array(
                new Index('PRIMARY', array('year', 'month', 'day', 'country'))
            ),
            'options' => array(
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '',
                'ENGINE' => 'MyISAM',
                'TABLE_COLLATION' => 'utf8_unicode_ci'
            )
        )
        );
    }
}
