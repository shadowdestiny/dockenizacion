<?php


namespace tests\unit;


use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\web\services\TransactionService;
use EuroMillions\web\vo\enum\TransactionType;

class TransactionServiceUnitTest extends UnitTestBase
{

    private $entityManagerDouble;
    private $transactionsRepository_double;

    public function setUp()
    {
        parent::setUp();
        $this->entityManagerDouble = $this->getEntityManagerDouble();
        $this->transactionsRepository_double = $this->getRepositoryDouble('TransactionRepository');
    }


    /**
     * method storeTransaction
     * when called
     * should createProperlyTransaction
     * @dataProvider getTransactionTypeAndExpected
     */
    public function test_storeTransaction_called_createProperlyTransaction($data,$type,$class, $expected)
    {
        $user_id = UserMother::aUserWith500Eur()->build()->getId();
        $now = new \DateTime();
        $sut = $this->getSut();
        $actual = $sut->storeTransaction($type, $data, $user_id, $now);
        $expected = new ActionResult($expected);
        $this->assertInstanceOf($class,$actual->getValues());
        $this->assertEquals($expected->success(),$actual->success());
    }

    public function getTransactionTypeAndExpected()
    {
        $dataManual = [
            'lottery_id' => 1,
            'numBets' => 3,
            'amountWithWallet' => 2000,
            'amountWithCreditCard' => 0,
            'feeApplied' => 0
        ];

        $dataAutomatic = [
            'lottery_id' => 1,
            'numBets' => 3,
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
        $data = [
            'lottery_id' => 1,
            'numBets' => 3,
        ];
        $user_id = UserMother::aUserWith500Eur()->build()->getId();
        $now = new \DateTime();
        $sut = $this->getSut();
        $actual = $sut->storeTransaction(TransactionType::TICKET_PURCHASE, $data, $user_id, $now);
        $expected = new ActionResult(false);
        $this->assertEquals($expected,$actual);
    }


    private function getSut()
    {
        return new TransactionService($this->entityManagerDouble->reveal());
    }
}