<?php


namespace EuroMillions\tests\unit;


use EuroMillions\tests\base\UnitTestBase;
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
        $data = [
            'lottery_id' => 1,
            'numBets' => 3,
            'amountWithWallet' => 2000,
            'amountWithCreditCard' => 0,
            'feeApplied' => 0
        ];
        $expected = new TicketPurchaseTransaction();
        $expected->setLotteryId($data['lottery_id']);
        $expected->setNumBets($data['numBets']);
        $expected->setAmountWithWallet($data['amountWithWallet']);
        $expected->setAmountWithCreditCard($data['amountWithCreditCard']);
        $expected->setFeeApplied($data['feeApplied']);
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