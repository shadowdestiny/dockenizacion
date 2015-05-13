<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

class AclResourcesMigration_100 extends Migration
{

    public function up()
    {
        $this->morphTable(
            'acl_resources',
            array(
            'columns' => array(
                new Column(
                    'resource_id',
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
                    'name',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 254,
                        'after' => 'resource_id'
                    )
                ),
                new Column(
                    'type',
                    array(
                        'type' => Column::TYPE_CHAR,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'name'
                    )
                ),
                new Column(
                    'category',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 64,
                        'after' => 'type'
                    )
                ),
                new Column(
                    'default_value',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 3,
                        'after' => 'category'
                    )
                )
            ),
            'indexes' => array(
                new Index('PRIMARY', array('resource_id')),
                new Index('name', array('name')),
                new Index('category', array('category')),
                new Index('type', array('type'))
            ),
            'options' => array(
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '40',
                'ENGINE' => 'InnoDB',
                'TABLE_COLLATION' => 'utf8_unicode_ci'
            )
        )
        );
    }
}
