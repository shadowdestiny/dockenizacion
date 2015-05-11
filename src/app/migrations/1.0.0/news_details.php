<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

class NewsDetailsMigration_100 extends Migration
{

    public function up()
    {
        $this->morphTable(
            'news_details',
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
                    'news_id',
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
                        'size' => 10,
                        'after' => 'news_id'
                    )
                ),
                new Column(
                    'alias',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 254,
                        'after' => 'lang'
                    )
                ),
                new Column(
                    'title',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 254,
                        'after' => 'alias'
                    )
                ),
                new Column(
                    'content',
                    array(
                        'type' => Column::TYPE_TEXT,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'title'
                    )
                ),
                new Column(
                    'page_title',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 512,
                        'after' => 'content'
                    )
                ),
                new Column(
                    'meta_keywords',
                    array(
                        'type' => Column::TYPE_TEXT,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'page_title'
                    )
                ),
                new Column(
                    'meta_description',
                    array(
                        'type' => Column::TYPE_TEXT,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'meta_keywords'
                    )
                ),
                new Column(
                    'published',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 4,
                        'after' => 'meta_description'
                    )
                ),
                new Column(
                    'published_on',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'published'
                    )
                ),
                new Column(
                    'published_by',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'published_on'
                    )
                ),
                new Column(
                    'published_by_name',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 512,
                        'after' => 'published_by'
                    )
                ),
                new Column(
                    'image',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 1024,
                        'after' => 'published_by_name'
                    )
                ),
                new Column(
                    'header',
                    array(
                        'type' => Column::TYPE_TEXT,
                        'size' => 1,
                        'after' => 'image'
                    )
                ),
                new Column(
                    'alt',
                    array(
                        'type' => Column::TYPE_TEXT,
                        'size' => 1,
                        'after' => 'header'
                    )
                ),
                new Column(
                    'description',
                    array(
                        'type' => Column::TYPE_TEXT,
                        'size' => 1,
                        'after' => 'alt'
                    )
                ),
                new Column(
                    'creation_date',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'description'
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
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
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
                ),
                new Column(
                    'count_view',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'changed_by'
                    )
                ),
                new Column(
                    'last_view',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'count_view'
                    )
                )
            ),
            'indexes' => array(
                new Index('PRIMARY', array('id')),
                new Index('alias', array('news_id', 'lang')),
                new Index('lang', array('lang')),
                new Index('alias_2', array('alias'))
            ),
            'options' => array(
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '243',
                'ENGINE' => 'MyISAM',
                'TABLE_COLLATION' => 'utf8_unicode_ci'
            )
        )
        );
    }
}
