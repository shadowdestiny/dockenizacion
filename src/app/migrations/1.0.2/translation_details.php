<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

class TranslationDetailsMigration_102 extends Migration
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
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 255,
                        'after' => 'lang'
                    )
                ),
                new Column(
                    'language_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'size' => 10,
                        'after' => 'value'
                    )
                )
            ),
            'references' => array(
                new Reference('FK_D32AF27882F1BAF4', array(
                    'referencedSchema' => 'euromillions',
                    'referencedTable' => 'languages',
                    'columns' => array('language_id'),
                    'referencedColumns' => array('id')
                )),
                new Reference('FK_D32AF2789CAA2B25', array(
                    'referencedSchema' => 'euromillions',
                    'referencedTable' => 'translations',
                    'columns' => array('translation_id'),
                    'referencedColumns' => array('translation_id')
                ))
            ),
            'options' => array(
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '2509',
                'ENGINE' => 'InnoDB',
                'TABLE_COLLATION' => 'utf8_unicode_ci'
            )
        )
        );
    }
}
