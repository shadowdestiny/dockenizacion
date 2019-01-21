<?php


namespace EuroMillions\tests\unit;


use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\BetMother;
use EuroMillions\web\vo\dto\PastDrawsCollectionDTO;


class PastDrawDTOCollectionUnitTest extends UnitTestBase
{
    /**
     * method createCollectionByLottery
     * when called
     * should ReturnArrayPlayConfigCollectionDTO
     */
    public function test_createCollectionByLottery_called_ReturnArrayPlayConfigCollectionDTO()
    {
        $bets= [[BetMother::aSingleBet()]];
        $actual=new PastDrawsCollectionDTO($bets,1);
        $this->assertEquals(1, isset($actual->result['EuroMillions']['2015-09-10']));
    }
}