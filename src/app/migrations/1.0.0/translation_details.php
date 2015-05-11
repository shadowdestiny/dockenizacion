<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

class TranslationDetailsMigration_100 extends Migration
{

    public function up()
    {
        $this->morphTable(
            'translation_details',
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
                    'translation_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'id'
                    )
                ),
                new Column(
                    'lang',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 6,
                        'after' => 'translation_id'
                    )
                ),
                new Column(
                    'value',
                    array(
                        'type' => Column::TYPE_TEXT,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'lang'
                    )
                )
            ),
            'indexes' => array(
                new Index('PRIMARY', array('id')),
                new Index('translation_id', array('translation_id', 'lang')),
                new Index('translation_id_2', array('translation_id')),
                new Index('value', array('value'))
            ),
            'options' => array(
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '2483',
                'ENGINE' => 'MyISAM',
                'TABLE_COLLATION' => 'utf8_unicode_ci'
            )
        )
        );
    }
}
