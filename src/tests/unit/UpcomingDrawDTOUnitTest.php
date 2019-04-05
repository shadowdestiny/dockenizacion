<?php
namespace EuroMillions\tests\unit;


use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\LotteryMother;
use EuroMillions\tests\helpers\mothers\PlayConfigMother;
use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\web\vo\dto\UpcomingDrawsDTO;


class UpcomingDrawDTOUnitTest extends UnitTestBase
{
    /**
     * method createCollectionByLottery
     * when called
     * should ReturnArrayPlayConfigCollectionDTO
     */
    public function test_createCollectionByLottery_called_ReturnArrayPlayConfigCollectionDTO()
    {
        $playConfig= [PlayConfigMother::aPlayConfig()->withLottery(LotteryMother::aEuroJackpot())->withUser(UserMother::anAlreadyRegisteredUser()->build())->build()];
        $actual=new UpcomingDrawsDTO($playConfig,1);
        $this->assertEquals(1, isset($actual->result['EuroJackpot']['2015-09-10']));
    }
}