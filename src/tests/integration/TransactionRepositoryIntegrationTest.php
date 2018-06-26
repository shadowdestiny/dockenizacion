<?php


namespace EuroMillions\tests\integration;


use EuroMillions\tests\base\RepositoryIntegrationTestBase;
use EuroMillions\web\entities\TicketPurchaseTransaction;
use EuroMillions\web\entities\WinningsWithdrawTransaction;
use EuroMillions\tests\helpers\mothers\UserMother;

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
          'transactions',
          'playconfig_transaction'
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


    public function test_add_calledToStoreTicketPurchaseTransaction_createAnewRowInPlayConfigTransactionTable()
    {

        $transactionString = '5#4';
        $date = new \DateTime();
        $user = UserMother::aJustRegisteredUser()->build();

        $now = new \DateTime();
        $data = [
            'lottery_id' => 1,
            'numBets' => 3,
            'amountWithWallet' => 2000,
            'amountWithCreditCard' => 0,
            'feeApplied' => 0,
            'user' => $user,
            'walletBefore' => $user->getWallet(),
            'walletAfter' => $user->getWallet(),
            'transactionID' => '123456',
            'now' => $now,
            'playConfigs' => [1,2],
            'discount' => 0,
        ];

        $transactionType = new TicketPurchaseTransaction($data);
        $this->entityManager->persist($user);
        $this->entityManager->persist($transactionType);
        $this->sut->add($transactionType);
        $this->entityManager->flush($transactionType);

        $actual = $this->entityManager
            ->createQuery(
                'SELECT pt'
                . ' FROM \EuroMillions\web\entities\PlayConfigTransaction pt')
            ->getResult();

        $this->isInstanceOf('PlayConfigTransaction',$actual[0]);
        $this->assertEquals(2,count($actual));
    }

    /**
     * method getLast
     * when called
     * should returnNextIdFromTransactionTable
     */
    public function test_getLast_called_returnNextIdFromTransactionTable()
    {
        $actual = $this->sut->getNextId();
        $this->assertEquals(9, $actual);
    }
    
}