<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

class TranslationsMigration_100 extends Migration
{

    public function up()
    {
        $this->morphTable(
            'translations',
            array(
            'columns' => array(
                new Column(
                    'translation_id',
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
                    'key',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 254,
                        'after' => 'translation_id'
                    )
                ),
                new Column(
                    'used',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 3,
                        'after' => 'key'
                    )
                )
            ),
            'indexes' => array(
                new Index('PRIMARY', array('translation_id')),
                new Index('key', array('key')),
                new Index('used', array('used'))
            ),
            'options' => array(
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '688',
                'ENGINE' => 'MyISAM',
                'TABLE_COLLATION' => 'utf8_unicode_ci'
            )
        )
        );
    }
}
