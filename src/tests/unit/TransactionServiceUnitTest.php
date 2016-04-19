<?php


namespace tests\unit;


use EuroMillions\shared\config\Namespaces;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\web\services\TransactionService;
use EuroMillions\web\vo\enum\TransactionType;
use Prophecy\Argument;

class TransactionServiceUnitTest extends UnitTestBase
{

    private $entityManagerDouble;
    private $transactionsRepository_double;


    protected function getEntityManagerStubExtraMappings()
    {
        return [
            Namespaces::ENTITIES_NS . 'Transaction'       => $this->transactionsRepository_double,
        ];
    }

    public function setUp()
    {
        parent::setUp();
        $this->entityManagerDouble = $this->getEntityManagerDouble();
        $this->transactionsRepository_double = $this->getRepositoryDouble('EuroMillions\web\entities\Transaction');
    }


    /**
     * method storeTransaction
     * when called
     * should createProperlyTransaction
     * @dataProvider getTransactionTypeAndExpected
     */
    public function test_storeTransaction_called_createProperlyTransaction($data,$type,$class, $expected)
    {
        $sut = $this->getSut();
        $this->entityManagerDouble->persist(Argument::type($class))->shouldBeCalled();
        $this->entityManagerDouble->flush()->shouldBeCalled();
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
            'now' => $now
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


    private function getSut()
    {
        return new TransactionService($this->entityManagerDouble->reveal());
    }
}