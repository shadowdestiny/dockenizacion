<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

class FaqsMigration_101 extends Migration
{

    public function up()
    {
        $this->morphTable(
            'faqs',
            array(
            'columns' => array(
                new Column(
                    'faq_id',
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
                        'size' => 4,
                        'after' => 'faq_id'
                    )
                ),
                new Column(
                    'pos',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'active'
                    )
                ),
                new Column(
                    'category',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 50,
                        'after' => 'pos'
                    )
                ),
                new Column(
                    'name',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 255,
                        'after' => 'category'
                    )
                ),
                new Column(
                    'creation_date',
                    array(
                        'type' => Column::TYPE_DATETIME,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'name'
                    )
                ),
                new Column(
                    'created_by',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'creation_date'
                    )
                ),
                new Column(
                    'change_date',
                    array(
                        'type' => Column::TYPE_DATETIME,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'created_by'
                    )
                ),
                new Column(
                    'changed_by',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'change_date'
                    )
                )
            ),
            'indexes' => array(
                new Index('PRIMARY', array('faq_id')),
                new Index('name', array('name')),
                new Index('active', array('active')),
                new Index('pos', array('pos')),
                new Index('category', array('category'))
            ),
            'options' => array(
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '31',
                'ENGINE' => 'InnoDB',
                'TABLE_COLLATION' => 'utf8_unicode_ci'
            )
        )
        );
    }
}
