<?php


namespace EuroMillions\tests\unit;


use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\web\components\transaction\TicketPurchaseGenerator;
use EuroMillions\web\entities\TicketPurchaseTransaction;

class TicketPurchaseGeneratorUnitTest extends UnitTestBase
{


    /**
     * method build
     * when called
     * should returnProperlyEntity
     */
    public function test_build_called_returnProperlyEntity()
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
            'now' => $now
        ];
        $expected = new TicketPurchaseTransaction();
        $expected->setLotteryId($data['lottery_id']);
        $expected->setNumBets($data['numBets']);
        $expected->setAmountWithWallet($data['amountWithWallet']);
        $expected->setAmountWithCreditCard($data['amountWithCreditCard']);
        $expected->setFeeApplied($data['feeApplied']);
        $expected->setData('1#3#2000#0#0');
        $expected->setUser($user);
        $expected->setDate($now);
        $expected->setWalletBefore($wallet_before);
        $expected->setWalletAfter($wallet_after);
        $sut = new TicketPurchaseGenerator();
        $actual = $sut->build($data);
        $this->assertEquals($expected,$actual);
        $this->assertInstanceOf('EuroMillions\web\entities\TicketPurchaseTransaction', $actual);
    }

    /**
     * method build
     * when called
     * should throwException
     */
    public function test_build_called_throwException()
    {
        $data = [
            'lottery_id' => 1,
            'numBets' => 3,
        ];
        $this->setExpectedException('\Exception');
        $sut = new TicketPurchaseGenerator();
        $sut->build($data);
    }


}