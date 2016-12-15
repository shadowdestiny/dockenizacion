<?php


namespace tests\unit;


use EuroMillions\shared\config\Namespaces;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\PlayConfigMother;
use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\web\components\transaction\detail\TicketPurchaseDetail;
use EuroMillions\web\entities\TicketPurchaseTransaction;

class TicketPurchaseDetailUnitTest extends UnitTestBase
{

    private $playConfigRepository_double;

    protected function getEntityManagerStubExtraMappings()
    {
        return [
            Namespaces::ENTITIES_NS . 'PlayConfig'       => $this->playConfigRepository_double,
        ];
    }

    public function setUp()
    {
        $this->playConfigRepository_double = $this->getRepositoryDouble('PlayConfigRepository');
        parent::setUp();
    }

    /**
     * method obtainDataForDetails
     * when called
     * should returnAProperDTO
     */
    public function test_obtainDataForDetails_called_returnAProperDTO()
    {
        $user = UserMother::aUserWith500Eur()->build();
        $now = new \DateTime();
        $wallet_before = $user->getWallet();
        $wallet_after = $user->getWallet();

        $playConfig = PlayConfigMother::aPlayConfig()->build();
        $playConfigTwo = PlayConfigMother::aPlayConfig()->build();
        $data = [
            'lottery_id' => 1,
            'numBets' => 3,
            'amountWithWallet' => 2000,
            'amountWithCreditCard' => 0,
            'feeApplied' => 0,
            'user' => $user,
            'walletBefore' => $wallet_before,
            'walletAfter' => $wallet_after,
            'transactionID' => uniqid(),
            'now' => $now,
            'playConfigs' => [1,2],
            'discount' => 0,
        ];

        $transaction = new TicketPurchaseTransaction($data);
        $transaction->toString();
        $sut = new TicketPurchaseDetail($this->getEntityManagerRevealed(),$transaction);
        $this->playConfigRepository_double->getPlayConfigsByCollectionIds([1,2])->willReturn([$playConfig,$playConfigTwo]);
        $actual = $sut->obtainDataForDetails();
        $this->assertCount(2,$actual);
        $this->assertInstanceOf('EuroMillions\web\vo\dto\TicketPurchaseDetailDTO',$actual[1]);
    }

}