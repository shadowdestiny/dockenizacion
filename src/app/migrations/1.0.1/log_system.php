<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

class LogSystemMigration_101 extends Migration
{

    public function up()
    {
        $this->morphTable(
            'log_system',
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
                    'level',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 255,
                        'after' => 'id'
                    )
                ),
                new Column(
                    'priority',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 255,
                        'after' => 'level'
                    )
                ),
                new Column(
                    'user_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'size' => 10,
                        'after' => 'priority'
                    )
                ),
                new Column(
                    'message',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 255,
                        'after' => 'user_id'
                    )
                ),
                new Column(
                    'created',
                    array(
                        'type' => Column::TYPE_DATETIME,
                        'size' => 1,
                        'after' => 'message'
                    )
                ),
                new Column(
                    'user_agent',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 1024,
                        'after' => 'created'
                    )
                ),
                new Column(
                    'referer',
                    array(
                        'type' => Column::TYPE_TEXT,
                        'size' => 1,
                        'after' => 'user_agent'
                    )
                ),
                new Column(
                    'uri',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 1024,
                        'after' => 'referer'
                    )
                ),
                new Column(
                    'get_vars',
                    array(
                        'type' => Column::TYPE_TEXT,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'uri'
                    )
                ),
                new Column(
                    'post_vars',
                    array(
                        'type' => Column::TYPE_TEXT,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'get_vars'
                    )
                ),
                new Column(
                    'ip',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 24,
                        'after' => 'post_vars'
                    )
                )
            ),
            'indexes' => array(
                new Index('PRIMARY', array('id')),
                new Index('user_id', array('user_id'))
            ),
            'options' => array(
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '35369',
                'ENGINE' => 'InnoDB',
                'TABLE_COLLATION' => 'utf8_unicode_ci'
            )
        )
        );
    }
}
