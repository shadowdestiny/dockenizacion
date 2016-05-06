<?php


namespace EuroMillions\tests\unit;


use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\web\components\transaction\DepositGenerator;
use EuroMillions\web\entities\DepositTransaction;

class DepositGeneratorUnitTest extends UnitTestBase
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
            'amount' => 2000,
            'now' => $now,
        ];
        $expected = new DepositTransaction($data);
        $expected->toString();
        $sut = new DepositGenerator();
        $actual = $sut->build($data);
        $this->assertEquals($expected,$actual);
        $this->assertInstanceOf('EuroMillions\web\entities\DepositTransaction', $actual);
    }

    /**
     * method build
     * when called
     * should throwException
     */
    public function test_build_called_throwException()
    {
        $data = [];
        $this->setExpectedException('\Exception');
        $sut = new DepositGenerator();
        $sut->build($data);
    }



}