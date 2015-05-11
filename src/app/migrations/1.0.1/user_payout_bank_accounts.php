<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

class UserPayoutBankAccountsMigration_101 extends Migration
{

    public function up()
    {
        $this->morphTable(
            'user_payout_bank_accounts',
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
                    'user_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'id'
                    )
                ),
                new Column(
                    'state',
                    array(
                        'type' => Column::TYPE_CHAR,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'user_id'
                    )
                ),
                new Column(
                    'country',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 6,
                        'after' => 'state'
                    )
                ),
                new Column(
                    'account_holder',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 255,
                        'after' => 'country'
                    )
                ),
                new Column(
                    'bank_name',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 255,
                        'after' => 'account_holder'
                    )
                ),
                new Column(
                    'bic',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 255,
                        'after' => 'bank_name'
                    )
                ),
                new Column(
                    'iban',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 255,
                        'after' => 'bic'
                    )
                ),
                new Column(
                    'account_number',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 255,
                        'after' => 'iban'
                    )
                )
            ),
            'indexes' => array(
                new Index('PRIMARY', array('id')),
                new Index('bic', array('bic')),
                new Index('iban', array('iban')),
                new Index('name', array('bank_name')),
                new Index('state', array('state')),
                new Index('user_id', array('user_id'))
            ),
            'options' => array(
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '23',
                'ENGINE' => 'InnoDB',
                'TABLE_COLLATION' => 'utf8_unicode_ci'
            )
        )
        );
    }
}
