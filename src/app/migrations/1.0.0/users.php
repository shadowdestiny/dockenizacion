<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

class UsersMigration_100 extends Migration
{

    public function up()
    {
        $this->morphTable(
            'users',
            array(
            'columns' => array(
                new Column(
                    'user_id',
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
                        'size' => 1,
                        'after' => 'user_id'
                    )
                ),
                new Column(
                    'role',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 16,
                        'after' => 'active'
                    )
                ),
                new Column(
                    'customer_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 3,
                        'after' => 'role'
                    )
                ),
                new Column(
                    'username',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 64,
                        'after' => 'customer_id'
                    )
                ),
                new Column(
                    'password',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 64,
                        'after' => 'username'
                    )
                ),
                new Column(
                    'locale',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 10,
                        'after' => 'password'
                    )
                ),
                new Column(
                    'lang',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'locale'
                    )
                ),
                new Column(
                    'country',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'lang'
                    )
                ),
                new Column(
                    'currency',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'country'
                    )
                ),
                new Column(
                    'user_code',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 32,
                        'after' => 'currency'
                    )
                ),
                new Column(
                    'verified_email',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'user_code'
                    )
                ),
                new Column(
                    'last_login',
                    array(
                        'type' => Column::TYPE_DATETIME,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'verified_email'
                    )
                ),
                new Column(
                    'last_action',
                    array(
                        'type' => Column::TYPE_DATETIME,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'last_login'
                    )
                ),
                new Column(
                    'last_ip',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 23,
                        'after' => 'last_action'
                    )
                ),
                new Column(
                    'budget',
                    array(
                        'type' => Column::TYPE_DECIMAL,
                        'notNull' => true,
                        'size' => 10,
                        'scale' => 2,
                        'after' => 'last_ip'
                    )
                ),
                new Column(
                    'win',
                    array(
                        'type' => Column::TYPE_DECIMAL,
                        'notNull' => true,
                        'size' => 16,
                        'scale' => 2,
                        'after' => 'budget'
                    )
                ),
                new Column(
                    'tmp_password',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 64,
                        'after' => 'win'
                    )
                )
            ),
            'indexes' => array(
                new Index('PRIMARY', array('user_id')),
                new Index('username_2', array('username')),
                new Index('active', array('active')),
                new Index('role', array('role')),
                new Index('user_code', array('user_code')),
                new Index('locale', array('locale')),
                new Index('lang', array('lang')),
                new Index('country', array('country')),
                new Index('currency', array('currency')),
                new Index('customer_id', array('customer_id')),
                new Index('verified_email', array('verified_email')),
                new Index('bugdet', array('budget')),
                new Index('password', array('password')),
                new Index('tmp_password', array('tmp_password'))
            ),
            'options' => array(
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '113743',
                'ENGINE' => 'MyISAM',
                'TABLE_COLLATION' => 'utf8_unicode_ci'
            )
        )
        );
    }
}
