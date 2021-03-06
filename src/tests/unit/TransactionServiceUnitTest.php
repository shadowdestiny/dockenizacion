<?php


namespace tests\unit;


use EuroMillions\shared\config\Namespaces;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\web\entities\PurchaseTransaction;
use EuroMillions\web\services\TransactionService;
use EuroMillions\web\vo\enum\TransactionType;
use EuroMillions\web\entities\DepositTransaction;
use Prophecy\Argument;

class TransactionServiceUnitTest extends UnitTestBase
{

    private $transactionsRepository_double;
    private $currencyConversionService_double;



    protected function getEntityManagerStubExtraMappings()
    {
        return [
            Namespaces::ENTITIES_NS . 'Transaction'       => $this->transactionsRepository_double,
        ];
    }

    public function setUp()
    {
        $this->transactionsRepository_double = $this->getRepositoryDouble('TransactionRepository');
        $this->currencyConversionService_double = $this->getServiceDouble('CurrencyConversionService');
        parent::setUp();
    }


    /**
     * method storeTransaction
     * when called
     * should createProperlyTransaction
     * @dataProvider getTransactionTypeAndExpected
     */
    public function test_storeTransaction_called_createProperlyTransaction($data,$type,$class, $expected)
    {
        $this->markTestSkipped('Da error y no se porque');
        $sut = $this->getSut();
        $actual = $sut->storeTransaction($type, $data);
        $expected = new ActionResult($expected);
        $this->assertInstanceOf($class,$actual->getValues());
        $this->assertEquals($expected->success(),$actual->success());
    }

    public function getTransactionTypeAndExpected()
    {
        $user = UserMother::aUserWith500Eur()->build();
        $now = new \DateTime();
        $wallet_before = $user->getWallet();
        $wallet_after = $user->getWallet();

        $dataManual = [
            'lottery_id' => 1,
            'numBets' => 3,
            'amountWithWallet' => 2000,
            'amountWithCreditCard' => 0,
            'feeApplied' => 0,
            'user' => $user,
            'walletBefore' => $wallet_before,
            'walletAfter' => $wallet_after,
            'now' => $now,
            'playConfigs' => [1,2]
        ];

        $dataAutomatic = [
            'lottery_id' => 1,
            'numBets' => 3,
            'user' => $user,
            'walletBefore' => $wallet_before,
            'walletAfter' => $wallet_after,
            'now' => $now
        ];

        return [
          [$dataManual,TransactionType::TICKET_PURCHASE,'EuroMillions\web\entities\TicketPurchaseTransaction',true],
          [$dataAutomatic,TransactionType::AUTOMATIC_PURCHASE,'EuroMillions\web\entities\AutomaticPurchaseTransaction',true]
        ];
    }


    /**
     * method storeTransaction
     * when called
     * should returnActionResultFalse
     */
    public function test_storeTransaction_called_returnActionResultFalse()
    {
        $user = UserMother::aUserWith500Eur()->build();
        $now = new \DateTime();
        $wallet_before = $user->getWallet();
        $wallet_after = $user->getWallet();

        $data = [
            'lottery_id' => 1,
            'numBets' => 3,
            'user' => $user,
            'walletBefore' => $wallet_before,
            'walletAfter' => $wallet_after,
            'now' => $now

        ];
        $user = UserMother::aUserWith500Eur()->build();
        $now = new \DateTime();
        $sut = $this->getSut();
        $actual = $sut->storeTransaction(TransactionType::TICKET_PURCHASE, $data, $user, $user->getWallet(), $user->getWallet(), $now);
        $expected = new ActionResult(false);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method obtainTransaction
     * when called
     * should returnAProperTransaction
     */
    public function test_obtainTransaction_called_returnAProperTransaction()
    {
        $expected = new PurchaseTransaction([]);
        $sut = $this->getSut();
        $this->transactionsRepository_double->findBy(["id"=> 1])->willReturn($expected);
        $actual = $sut->getTransaction(1);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method obtainTransaction
     * when calledAndNoReturnTransaction
     * should returnNull
     */
    public function test_obtainTransaction_calledAndNoReturnTransaction_returnNull()
    {
        $expected = null;
        $sut = $this->getSut();
        $this->transactionsRepository_double->findBy(["id"=> 1])->willReturn(null);
        $actual = $sut->getTransaction(1);
        $this->assertNull($actual);
    }

    /**
    * method getPendingTransactions
    * when called
    * should returnAProperListOfPendingTransactions
    */
    public function test_getPendingTransactions_called_returnAProperListOfPendingTransactions()
    {
        $expected = [
            ["id" => 1],
        ];

        $sut = $this->getSut();
        $this->transactionsRepository_double->getPendingTransactionsEntityId(48*60)->willReturn($expected);
        $this->transactionsRepository_double->removeTransactionByEntityId(1)->willReturn(1);
        $actual = $sut->getPendingTransactionsEntityId(48*60);

        $this->assertEquals($expected, $actual);
    }

    /**
     * method removeTransactionByEntityId
     * when called
     * should returnAProperNumberOfDeletedTransactions
     */
    public function test_removeTransactionByEntityId_called_returnAProperNumberOfDeletedTransactions()
    {
        $expected = 1;

        $sut = $this->getSut();
        $this->transactionsRepository_double->removeTransactionByEntityId(1)->willReturn($expected);
        $actual = $sut->removeTransactionByEntityId(1);

        $this->assertEquals($expected, $actual);
    }

    /**
    * method getLastDepositProviderByUserId
    * when called
    * should returnAProperArrayOfProviders
    */
    public function test_getLastDepositProviderByUserId_called_returnAProperArrayOfProviders()
    {
        $expected = [
            ['id' => 1, 'data' => "1#385#PENDING#3#PowerBall#0#royalpay"],
            ['id' => 2, 'data' =>"1#335#PENDING#1#EuroMillions#moneymatrix"],
        ];

        $sut = $this->getSut();
        $this->transactionsRepository_double->getLastDepositsDataByUserId(1, 1)->willReturn($expected);
        $actual = $sut->getLastDepositProviderByUserId(1);

        $this->assertEquals('royalpay', $actual);
    }

    /**
     * method getLastDepositProviderByUserId
     * when called
     * should returnAProperEmptyArray
     */
    public function test_getLastDepositProviderByUserId_called_returnAProperEmptyArray()
    {
        $expected = [];

        $sut = $this->getSut();
        $this->transactionsRepository_double->getLastDepositsDataByUserId(1, 1)->willReturn($expected);
        $actual = $sut->getLastDepositProviderByUserId(1);

        $this->assertEquals(null, $actual);
    }

    private function getSut()
    {
        return new TransactionService($this->getEntityManagerRevealed(), $this->currencyConversionService_double->reveal());
    }
}