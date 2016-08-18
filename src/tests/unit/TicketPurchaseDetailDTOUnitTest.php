<?php


namespace tests\unit;


use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\PlayConfigMother;
use EuroMillions\web\vo\dto\TicketPurchaseDetailDTO;

class TicketPurchaseDetailDTOUnitTest extends UnitTestBase
{

    /**
     * method __construct
     * when called
     * should createAProperDTOWithProperData
     */
    public function test___construct_called_createAProperDTOWithProperData()
    {
        $playConfig = PlayConfigMother::aPlayConfig()->build();
        $actual = new TicketPurchaseDetailDTO($playConfig);
        $this->assertEquals('7,15,16,17,22',$actual->regularNumbers);
        $this->assertEquals('1,7',$actual->luckyNumbers);
        $this->assertEquals('2015-09-10',$actual->drawDate);
    }

}