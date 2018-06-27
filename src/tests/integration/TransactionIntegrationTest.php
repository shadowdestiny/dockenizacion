<?php


namespace EuroMillions\tests\integration;


use EuroMillions\shared\vo\Wallet;
use EuroMillions\tests\base\DatabaseIntegrationTestBase;
use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\web\entities\BetTransaction;
use EuroMillions\web\entities\FundsAddedTransaction;
use EuroMillions\web\entities\TicketPurchaseTransaction;
use EuroMillions\web\repositories\TransactionRepository;


class TransactionIntegrationTest extends DatabaseIntegrationTestBase
{

    /** @var  TransactionRepository */
    protected $transactionRepository;
    
    /**
     * Child classes must implement this method. Return empty array if no fixtures are needed
     * @return array
     */
    protected function getFixtures()
    {
        return [
            'transactions',
            'users'
        ];
    }

    public function setUp()
    {
        $this->transactionRepository = $this->entityManager->getRepository($this->getEntitiesToArgument('Transaction'));
        parent::setUp();
    }


    /**
     * method ticketPurchaseTransaction
     * when called
     * should createNewRecord
     */
    public function test_ticketPurchaseTransaction_called_createNewRecord()
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
        $transactionType->setDate($date);
        $transactionType->setData($transactionString);
        $transactionType->setEntityType('ticket_purchase');
        $transactionType->setUser($user);
        $wallet_before = new Wallet();
        $wallet_before->create(0,0);
        $transactionType->setWalletBefore($wallet_before);
        $this->entityManager->persist($user);
        $this->entityManager->persist($transactionType);
        $this->transactionRepository->add($transactionType);
        $this->entityManager->flush($transactionType);
        $actual = $this->entityManager
            ->createQuery(
                'SELECT t'
                . ' FROM \EuroMillions\web\entities\Transaction t')
            ->getResult();
        $this->assertCount(8,$actual);
        $this->assertEquals('ticket_purchase',$actual[0]->getEntityType());
    }

    /**
     * method fromString
     * when called
     * should returnEntityWithProperlyDataSetting
     */
    public function test_fromString_called_returnEntityWithProperlyDataSetting()
    {
        $actual = $this->entityManager
            ->createQuery(
                'SELECT t'
                . ' FROM \EuroMillions\web\entities\Transaction t')
            ->getResult();

        $this->assertEquals('2000',$actual[1]->fromString()->getAmountAdded());
        $this->assertEquals('1',$actual[1]->fromString()->getHasFee());
    }

    /**
     * method fromString
     * when called
     * should throwException
     */
    public function test_fromString_called_throwException()
    {
        $this->setExpectedException('\Exception','Invalid data format');

        $actual = $this->entityManager
            ->createQuery(
                'SELECT t'
                . ' FROM \EuroMillions\web\entities\Transaction t')
            ->getResult();

        $actual[0]->fromString();
    }

    /**
     * method fromString
     * when called
     * should returnEntityWithProperlyData
     */
    public function test_fromString_called_returnEntityWithProperlyData()
    {
        $actual = $this->entityManager
            ->createQuery(
                'SELECT t'
                . ' FROM \EuroMillions\web\entities\Transaction t')
            ->getResult();

        $this->assertEquals(1,$actual[2]->fromString()->getLotteryId());
        $this->assertEquals(4,$actual[2]->fromString()->getNumBets());
        $this->assertEquals(0,$actual[2]->fromString()->getAmountWithWallet());
        $this->assertEquals(2000,$actual[2]->fromString()->getAmountWithCreditCard());
        $this->assertEquals(0,$actual[2]->fromString()->getFeeApplied());
        $this->assertEquals([1,2],$actual[2]->fromString()->getPlayConfigs());
        $this->assertEquals(0,$actual[2]->fromString()->getDiscount());
    }

    /**
     * method getToString
     * when called
     * should returnEntityTypeInfo
     */
    public function test_getToString_called_returnEntityTypeInfo()
    {
        $actual = $this->entityManager
            ->createQuery(
                'SELECT t'
                . ' FROM \EuroMillions\web\entities\Transaction t')
            ->getResult();

        $this->assertInstanceOf('EuroMillions\web\entities\TicketPurchaseTransaction',$actual[0]);

    }


    public function test_rebuildEntityFromData_called_returnEntityWithProperties()
    {
        $class = 'EuroMillions\web\entities\SubscriptionPurchaseTransaction';
        $actual = $this->entityManager
            ->createQuery(
                'SELECT t'
                . ' FROM \EuroMillions\web\entities\Transaction t '
                . ' where t INSTANCE OF '.$class )
            ->getResult();



    }


    /**
     * method getTransactionByType
     * when called
     * should returnCollectionWithRowsFromTransactionType
     */
    public function test_getTransactionByType_called_returnCollectionWithRowsFromTransactionType()
    {
        $class = 'EuroMillions\web\entities\WinningsWithdrawTransaction';
        $actual = $this->entityManager
            ->createQuery(
                'SELECT t'
                . ' FROM \EuroMillions\web\entities\Transaction t '
                . ' where t INSTANCE OF '.$class )
            ->getResult();

        $this->assertEquals(2,count($actual));
    }
    
    
}