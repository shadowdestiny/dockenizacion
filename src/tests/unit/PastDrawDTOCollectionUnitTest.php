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

    /**
     * method createCollectionByDate
     * when called
     * should returnAProperCollectionPastDrawDTO
     */
    public function test_createCollectionByDate_called_returnAProperCollectionPastDrawDTO()
    {
        $bet=BetMother::aSingleBet();
        $bets= [[$bet,
            'startDrawDate' => $bet->getPlayConfig()->getStartDrawDate(),
            'line.regular_number_one' => 1,
            'line.regular_number_two' => 2,
            'line.regular_number_three' => 3,
            'line.regular_number_four' => 4,
            'line.regular_number_five' => 5,
            'line.lucky_number_one' => 6,
            'line.lucky_number_two' => 7]];
        $actual=new PastDrawsCollectionDTO($bets);

        $this->assertEquals(1, isset($actual->result['dates']['2015-09-10']['EuroMillions']));
    }
}