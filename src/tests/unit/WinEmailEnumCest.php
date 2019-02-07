<?php

use EuroMillions\shared\enums\WinEmailEnum;

class WinEmailEnumCest
{
    /**
     * method findTemplatePathByLotteryName
     * when hasLotteryName
     * should returnTheCorrectTemplate
     * @param UnitTester $I
     * @group email-enum
     */
    public function test_findTemplatePathByLotteryName_hasLotteryName_returnTheCorrectTemplate(UnitTester $I)
    {
        foreach ($this->getSmallTemplate() as $lotteryName => $expectedTemplate) {
            $template = (new WinEmailEnum())->findTemplatePathByLotteryName($lotteryName, $isBig = false);
            $I->assertEquals($template, $expectedTemplate);
        }
    }

    /**
     * method findTemplatePathByLotteryName
     * when hasLotteryName
     * should returnTheCorrectAboveTemplate
     * @param UnitTester $I
     * @group email-enum
     */
    public function test_findTemplatePathByLotteryName_hasLotteryName_returnTheCorrectAboveTemplate(UnitTester $I)
    {
        foreach ($this->getBigTemplate() as $lotteryName => $expectedTemplate) {
            $template = (new WinEmailEnum())->findTemplatePathByLotteryName($lotteryName, $isBig = true);
            $I->assertEquals($template, $expectedTemplate);
        }
    }

    /**
     * method findTemplatePathByLotteryName
     * when hasFakeLotteryName
     * should throwUnexpectedValueException
     * @param UnitTester $I
     * @group email-enum
     */
    public function test_findTemplatePathByLotteryName_hasFakeLotteryName_throwUnexpectedValueException(UnitTester $I)
    {
        try {
            (new WinEmailEnum())->findTemplatePathByLotteryName('FakeLottery');
        }
        catch (Exception $e) {
            $I->assertTrue($e instanceof \UnexpectedValueException);
        }
    }

    protected function getSmallTemplate()
    {
        return [
            'EuroMillions' => 'EuroMillions\web\emailTemplates\WinEmailTemplate',
            'PowerBall' => 'EuroMillions\web\emailTemplates\WinEmailPowerBallTemplate',
            'MegaMillions' => 'EuroMillions\megamillions\emailTemplates\WinEmailMegaMillionsTemplate',
            'EuroJackpot' => 'EuroMillions\eurojackpot\emailTemplates\WinEmailEuroJackpotTemplate',
        ];
    }

    protected function getBigTemplate()
    {
        return [
            'EuroMillions' => 'EuroMillions\web\emailTemplates\WinEmailAboveTemplate',
            'PowerBall' => 'EuroMillions\web\emailTemplates\WinEmailPowerBallAboveTemplate',
            'MegaMillions' => 'EuroMillions\megamillions\emailTemplates\WinEmailMegaMillionsAboveTemplate',
            'EuroJackpot' => 'EuroMillions\eurojackpot\emailTemplates\WinEmailEuroJackpotAboveTemplate',
        ];
    }
}
