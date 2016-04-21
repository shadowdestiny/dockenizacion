<?php


namespace EuroMillions\tests\integration;


use EuroMillions\tests\base\RepositoryIntegrationTestBase;
use EuroMillions\web\entities\WinningsWithdrawTransaction;

class TransactionRepositoryIntegrationTest extends RepositoryIntegrationTestBase
{

    /** @var \EuroMillions\web\repositories\TransactionRepository */
    protected $sut;

    /**
     * Child classes must implement this method. Return empty array if no fixtures are needed
     * @return array
     */
    protected function getFixtures()
    {
        return [
          'users',
          'transactions'
        ];
    }

    protected function getEntity()
    {
        return 'Transaction';
    }

    /**
     * method getTransactionsByType
     * when called
     * should returnCollectionWithTransactionType
     */
    public function test_getTransactionsByType_called_returnCollectionWithTransactionType()
    {
        $transaction = new WinningsWithdrawTransaction();
        $actual = $this->sut->getTransactionsByType($transaction);
        $this->assertEquals(2,count($actual));
    }
}