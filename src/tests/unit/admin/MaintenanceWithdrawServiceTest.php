<?php


namespace EuroMillions\tests\unit\admin;



use EuroMillions\admin\services\MaintenanceWithdrawService;
use EuroMillions\shared\config\Namespaces;
use EuroMillions\shared\vo\Wallet;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\web\entities\TicketPurchaseTransaction;
use EuroMillions\web\entities\WinningsWithdrawTransaction;
use EuroMillions\web\vo\dto\WithdrawTransactionDTO;

class MaintenanceWithdrawServiceTest extends UnitTestBase
{

    private $transactionRespository_double;


    protected function getEntityManagerStubExtraMappings()
    {
        return [
            Namespaces::ENTITIES_NS . 'Transaction' => $this->transactionRespository_double,
        ];
    }

    public function setUp()
    {
        $this->transactionRespository_double = $this->getRepositoryDouble('TransactionRepository');
        parent::setUp();
    }

    /**
     * method fetchAll
     * when called
     * should returnProperDTOCollection
     */
    public function test_fetchAll_called_returnProperDTOCollection()
    {
        list($transaction, $expected) = $this->prepareWithdraw();
        $this->transactionRespository_double->getTransactionsByType($transaction)->willReturn([$transaction]);
        $sut = $this->getSut();
        $actual = $sut->fetchAll($transaction);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method changeState
     * when called
     * should changeStateFromTransaction
     */
    public function test_changeState_called_changeStateFromTransaction()
    {
        $idTransaction = 1;
        $state = 'approved';
        list($transaction, $expected) = $this->prepareWithdraw();
        $this->transactionRespository_double->find($idTransaction)->willReturn($transaction);
        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush()->shouldBeCalled();
        $entityManager_stub->persist($transaction)->shouldBeCalled();
        $sut = $this->getSut();
        $sut->changeState($idTransaction,$state);
    }

    /**
     * method changeState
     * when called
     * should throwException
     */
    public function test_changeState_called_throwException()
    {
        $this->setExpectedException('\Exception', 'Sorry, it was a problem. Try again.');
        $idTransaction = 1;
        $state = 'approved';
        $data = [
            'lottery_id' => 1,
            'numBets' => 1,
            'amountWithWallet' => 0,
            'amountWithCreditCard' => 0,
            'feeApplied' => 0,
            'walletBefore' => Wallet::create(),
            'walletAfter' => Wallet::create(),
            'now' => '',
            'user' => ''
        ];
        $transaction = new TicketPurchaseTransaction($data);
        $sut = $this->getSut();
        $sut->changeState($idTransaction,$state, $transaction);

    }


    private function getSut()
    {
        return new MaintenanceWithdrawService($this->getEntityManagerDouble()->reveal());
    }

    /**
     * @return array
     */
    private function prepareWithdraw()
    {
        $user = UserMother::aUserWith40EurWinnings()->build();
        $transaction = new WinningsWithdrawTransaction();
        $transaction->setState('pending');
        $transaction->setWalletBefore(Wallet::create());
        $transaction->setWalletAfter(Wallet::create());
        $transaction->setAccountBankId(1);
        $transaction->setDate(new \DateTime());
        $transaction->setAmountWithdrawed(25);
        $transaction->toString();
        $transaction->setUser($user);
        $expected = [new WithdrawTransactionDTO($transaction)];
        return array($transaction, $expected);
    }

}