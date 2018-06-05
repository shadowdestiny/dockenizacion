<?php


namespace EuroMillions\tests\unit\admin;



use Codeception\Step\Action;
use EuroMillions\admin\services\MaintenanceWithdrawService;
use EuroMillions\shared\config\Namespaces;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\shared\vo\Wallet;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\web\entities\TicketPurchaseTransaction;
use EuroMillions\web\entities\WinningsWithdrawTransaction;
use EuroMillions\web\vo\dto\WithdrawTransactionDTO;
use Prophecy\Argument;

class MaintenanceWithdrawServiceTest extends UnitTestBase
{

    private $transactionRespository_double;

    private $userRepository_double;


    protected function getEntityManagerStubExtraMappings()
    {
        return [
            Namespaces::ENTITIES_NS . 'Transaction' => $this->transactionRespository_double,
            Namespaces::ENTITIES_NS . 'User' => $this->userRepository_double,
        ];
    }

    public function setUp()
    {
        $this->transactionRespository_double = $this->getRepositoryDouble('TransactionRepository');
        $this->userRepository_double = $this->getRepositoryDouble('UserRepository');
        parent::setUp();
    }

    /**
     * method getLastTransactionIdByUser
     * when called
     * should returnLastTransactionIdCreatedByUser
     */
    public function test_getLastGwuidByUser_called_returnLastTransactionIdCreatedByUser()
    {
        $userID='fdsafdsafd456';
        $expected = '123456';
        $this->transactionRespository_double->getLastTransactionIDAsPurchaseType($userID)->willReturn('123456');
        $sut = $this->getSut();
        $actual = $sut->getLastTransactionIDByUser($userID);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method confirmWithDraw
     * when called
     * should returnSuccessfullyResponse
     */
    public function test_confirmWithDraw_called_returnSuccessfullyResponse()
    {
        $idWithDrawRequest = 1;
        $idTransaction = 1;
        list($transaction, $expected) = $this->prepareWithdraw();
        $expected = new ActionResult(true);
        $this->transactionRespository_double->find($idWithDrawRequest)->willReturn($transaction);
        $sut = $this->getSut();
        $actual = $sut->confirmWithDraw(1,1);
    }


    /**
     * method giveBackAmountToUserWallet
     * when called
     * should returnSuccessAndIncreaseUserWalletWithAmountWithdraw
     */
    public function test_giveBackAmountToUserWallet_returnSuccessAndIncreaseUserWalletWithAmountWithdraw()
    {
        $id = 1;
        list($transaction, $expected) = $this->prepareWithdraw('rejected');
        $expected = new ActionResult(true);
        $this->transactionRespository_double->find($id)->willReturn($transaction);
        $this->userRepository_double->add(Argument::any())->shouldBeCalled();
        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush(Argument::any())->shouldBeCalled();
        $sut = $this->getSut();
        $actual = $sut->giveBackAmountToUserWallet($id);
        $this->assertEquals($expected,$actual);
        $this->assertEquals(5500, $transaction->getUser()->getWallet()->getBalance()->getAmount());
        $this->assertEquals('rejected',$transaction->getState());
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
            'transactionID' => uniqid(),
            'now' => '',
            'user' => '',
            'playConfigs' => [1,2],
            'discount' => 0,
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
    private function prepareWithdraw($state='pending')
    {
        $user = UserMother::aUserWith40EurWinnings()->build();
        $transaction = new WinningsWithdrawTransaction();
        $transaction->setState($state);
        $transaction->setWalletBefore(Wallet::create());
        $transaction->setWalletAfter(Wallet::create());
        $transaction->setAccountBankId(1);
        $transaction->setDate(new \DateTime());
        $transaction->setAmountWithdrawed(2500);
        $transaction->toString();
        $transaction->setUser($user);
        $expected = [new WithdrawTransactionDTO($transaction)];
        return array($transaction, $expected);
    }

}