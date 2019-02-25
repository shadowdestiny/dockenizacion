<?php


namespace EuroMillions\tests\unit;


use EuroMillions\shared\enums\PurchaseConfirmationEnum;
use EuroMillions\tests\base\UnitTestBase;

class PurchaseConfirmationEnumUnitTest extends UnitTestBase
{

    /**
     * method findTemplatePathByLotteryName
     * when calledWithoutSubscription
     * should returnProperPathToTemplate
     * @dataProvider getLotteriesAndPaths
     */
    public function test_findTemplatePathByLotteryName_calledWithoutSubscription_returnProperPathToTemplate($lotteryName,$path)
    {
        $sut = new PurchaseConfirmationEnum();
        $actual = $sut->findTemplatePathByLotteryName($lotteryName);
        $this->assertEquals($path,$actual);
    }

    /**
     * method findTemplatePathByLotteryName
     * when calledWithSubscription
     * should returnProperPathTemplate
     */
    public function test_findTemplatePathByLotteryName_calledWithSubscription_returnProperPathTemplate()
    {
        $path = 'EuroMillions\megamillions\emailTemplates\MegaMillionsPurchaseSubscriptionConfirmationEmailTemplate';
        $sut = new PurchaseConfirmationEnum();
        $actual = $sut->findTemplatePathByLotteryName('MegaMillions',true);
        $this->assertEquals($path,$actual);
    }

    /**
     * method findTemplatePathByLotteryName
     * when passALotteryUnknown
     * should throughAnUnexpectedValueException
     */
    public function test_findTemplatePathByLotteryName_passALotteryUnknown_throughAnUnexpectedValueException()
    {
        $this->setExpectedException('\UnexpectedValueException');
        $sut = new PurchaseConfirmationEnum();
        $sut->findTemplatePathByLotteryName('Pepito');
    }

    public function getLotteriesAndPaths()
    {
        return [
            ['EuroJackpot','EuroMillions\eurojackpot\emailTemplates\EuroJackpotPurchaseConfirmationEmailTemplate'],
            ['MegaMillions','EuroMillions\megamillions\emailTemplates\MegaMillionsPurchaseConfirmationEmailTemplate'],
            ['EuroMillions','EuroMillions\web\emailTemplates\EuroMillionsPurchaseConfirmationEmailTemplate']
        ];
    }

}