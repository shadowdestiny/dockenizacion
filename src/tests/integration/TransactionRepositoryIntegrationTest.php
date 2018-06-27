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
            'lotteries',
          'transactions',
          'play_configs',
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
        $this->assertEquals(6,count($actual));
    }

    public function test_getSubscriptionBalanceByLottery_called_returnProperBalanceSubscriptionByLottery()
    {
        $lotteryId=3;
        $userId = '9098299B-14AC-4124-8DB0-19571EDABE60';
        $actual = $this->sut->getSubscriptionBalanceByLottery($lotteryId,$userId);
        $this->assertEquals(4900,$actual);
    }

    /**
     * method getLast
     * when called
     * should returnNextIdFromTransactionTable
     */
    public function test_getLast_called_returnNextIdFromTransactionTable()
    {
        $actual = $this->sut->getNextId();
        $this->assertEquals(11, $actual);
    }
    
}