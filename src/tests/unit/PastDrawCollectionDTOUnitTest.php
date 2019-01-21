<?php


namespace EuroMillions\tests\unit;


use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\builders\BetBuilder;
use EuroMillions\tests\helpers\builders\PlayConfigBuilder;
use EuroMillions\tests\helpers\mothers\BetMother;
use EuroMillions\web\vo\dto\PastDrawsCollectionDTO;

class PastDrawCollectionDTOUnitTest extends UnitTestBase
{

    /**
     * method __construct
     * when called
     * should returnAProperCollectionPastDrawDTO
     */
    public function test___construct_called_returnAProperCollectionPastDrawDTO()
    {
        //@vapdl Please add anBet method to pass the test
        $this->markTestSkipped('Miss anBet method in BetBuilder');
        $expected = [PlayConfigBuilder::aPlayConfig()->build()];
        $betArr = [BetBuilder::anBet()->build()];
        $actual = new PastDrawsCollectionDTO($betArr,1);
        $this->assertEquals($expected,$actual->result);
    }



}