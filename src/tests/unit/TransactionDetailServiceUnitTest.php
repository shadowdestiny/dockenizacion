<?php


namespace tests\unit;


use EuroMillions\shared\config\Namespaces;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\web\entities\TicketPurchaseTransaction;
use EuroMillions\web\services\TransactionDetailService;
use Prophecy\Argument;

class TransactionDetailServiceUnitTest extends UnitTestBase
{

    private $transactionsRepository_double;
    private $playConfigRepository_double;
    private $currencyConversionService_double;



    protected function getEntityManagerStubExtraMappings()
    {
        return [
            Namespaces::ENTITIES_NS . 'Transaction'       => $this->transactionsRepository_double,
            Namespaces::ENTITIES_NS . 'PlayConfig'       => $this->playConfigRepository_double,
        ];
    }

    public function setUp()
    {
        $this->transactionsRepository_double = $this->getRepositoryDouble('TransactionRepository');
        $this->currencyConversionService_double = $this->getServiceDouble('CurrencyConversionService');
        $this->playConfigRepository_double = $this->getRepositoryDouble('PlayConfigRepository');
        parent::setUp();
    }

    /**
     * method obtainTransactionDetails
     * when called
     * should returnDTOObjectWithProperDetailsFromTransaction
     */
    public function test_obtainTransactionDetails_called_returnDTOObjectWithProperDetailsFromTransaction()
    {
        $user = UserMother::aUserWith500Eur()->build();
        $now = new \DateTime();
        $wallet_before = $user->getWallet();
        $wallet_after = $user->getWallet();
        $data = [
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


        $transaction = new TicketPurchaseTransaction($data);
        $iTransactionDetailStrategy = $this->prophesize('EuroMillions\web\interfaces\ITransactionDetailStrategy');
        $transaction->setEntityType('ticket_purchase');
        $iTransactionDetailStrategy->obtainDataForDetails()->willReturn([Argument::any(),Argument::any()]);
        $sut = new TransactionDetailService($this->getEntityManagerRevealed(),$this->currencyConversionService_double->reveal());
        $actual = $sut->obtainTransactionDetails($transaction,$iTransactionDetailStrategy->reveal());
        $this->assertCount(2,$actual);
    }

    /**
     * method obtainTransactionDetails
     * when calledWithTransactionTypeDifferentTicketPurchaseAndWinnings
     * should returnNull
     */
    public function test_obtainTransactionDetails_calledWithTransactionTypeDifferentTicketPurchaseAndWinnings_returnNull()
    {

    }


}