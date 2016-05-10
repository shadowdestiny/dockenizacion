<?php


namespace EuroMillions\tests\unit;


use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\web\components\transaction\BigWinGenerator;
use EuroMillions\web\entities\BigWinTransaction;

class BigWinGeneratorUnitTest extends UnitTestBase
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
            'draw_id' => 1,
            'bet_id'  => 1,
            'amount'  => 30000,
            'state'   => 'pending',
            'user'    => $user,
            'walletBefore' => $wallet_before,
            'walletAfter'  => $wallet_after,
            'now'          => $now
        ];

        $expected = new BigWinTransaction($data);
        $expected->toString();
        $sut = new BigWinGenerator();
        $actual = $sut->build($data);
        $this->assertEquals($expected->getBetId(),$actual->getBetId());
        $this->assertEquals($expected->getDrawId(),$actual->getDrawId());
        $this->assertInstanceOf(BigWinTransaction::class,$actual);
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
        $sut = new BigWinGenerator();
        $sut->build($data);
    }



}