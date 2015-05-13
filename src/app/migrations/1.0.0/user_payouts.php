<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

class UserPayoutsMigration_100 extends Migration
{

    public function up()
    {
        $this->morphTable(
            'user_payouts',
            array(
            'columns' => array(
                new Column(
                    'payout_id',
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
                        'after' => 'payout_id'
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
                    'payout_account_type',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 255,
                        'after' => 'state'
                    )
                ),
                new Column(
                    'payout_account_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'payout_account_type'
                    )
                ),
                new Column(
                    'amount',
                    array(
                        'type' => Column::TYPE_DECIMAL,
                        'notNull' => true,
                        'size' => 10,
                        'scale' => 2,
                        'after' => 'payout_account_id'
                    )
                ),
                new Column(
                    'transaction_id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'amount'
                    )
                ),
                new Column(
                    'add_date',
                    array(
                        'type' => Column::TYPE_DATETIME,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'transaction_id'
                    )
                ),
                new Column(
                    'payout_date',
                    array(
                        'type' => Column::TYPE_DATETIME,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'add_date'
                    )
                ),
                new Column(
                    'payout_by',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 10,
                        'after' => 'payout_date'
                    )
                ),
                new Column(
                    'comment',
                    array(
                        'type' => Column::TYPE_TEXT,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'payout_by'
                    )
                )
            ),
            'indexes' => array(
                new Index('PRIMARY', array('payout_id')),
                new Index('user_id', array('user_id')),
                new Index('state', array('state')),
                new Index('amount', array('amount')),
                new Index('transaction_id', array('transaction_id')),
                new Index('add_date', array('add_date')),
                new Index('payout_date', array('payout_date'))
            ),
            'options' => array(
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '21',
                'ENGINE' => 'InnoDB',
                'TABLE_COLLATION' => 'utf8_unicode_ci'
            )
        )
        );
    }
}
