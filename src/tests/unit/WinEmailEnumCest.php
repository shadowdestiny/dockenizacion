<?php

use EuroMillions\shared\enums\WinEmailEnum;

class WinEmailEnumCest
{
    /**
     * @var EuroMillions\shared\enums\WinEmailEnum
     */
    protected $winEmailEnum;

    public function _before(\UnitTester $I)
    {
        $this->winEmailEnum = new WinEmailEnum();
    }

    /**
     * method findTemplatePathByLotteryName
     * when hasLotteryName
     * should returnTheCorrectTemplate
     * @param UnitTester $I
     * @group email-enum
     * @dataProvider getSmallTemplate
     */
    public function test_findTemplatePathByLotteryName_hasLotteryName_returnTheCorrectTemplate(UnitTester $I, \Codeception\Example $data)
    {
        $template = $this->winEmailEnum->findTemplatePathByLotteryName($data['lottery'], $isBig = false);
        $I->assertEquals($template, $data['template']);
    }

    /**
     * method findTemplatePathByLotteryName
     * when hasLotteryName
     * should returnTheCorrectAboveTemplate
     * @param UnitTester $I
     * @group email-enum
     * @dataProvider getBigTemplate
     */
    public function test_findTemplatePathByLotteryName_hasLotteryName_returnTheCorrectAboveTemplate(UnitTester $I, \Codeception\Example $data)
    {
        $template = $this->winEmailEnum->findTemplatePathByLotteryName($data['lottery'], $isBig = true);
        $I->assertEquals($template, $data['template']);
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
            $this->winEmailEnum->findTemplatePathByLotteryName('FakeLottery');
        }
        catch (Exception $e) {
            $I->assertTrue($e instanceof \UnexpectedValueException);
        }
    }

    /**
     * @return array
     */
    protected function getSmallTemplate()
    {
        return [
            ['lottery' => 'EuroMillions',   'template' => 'EuroMillions\web\emailTemplates\WinEmailTemplate'],
            ['lottery' => 'PowerBall',      'template' => 'EuroMillions\web\emailTemplates\WinEmailPowerBallTemplate'],
            ['lottery' => 'MegaMillions',   'template' => 'EuroMillions\megamillions\emailTemplates\WinEmailMegaMillionsTemplate'],
            ['lottery' => 'EuroJackpot',    'template' => 'EuroMillions\eurojackpot\emailTemplates\WinEmailEuroJackpotTemplate'],
        ];
    }

    /**
     * @return array
     */
    protected function getBigTemplate()
    {
        return [
            ['lottery' => 'EuroMillions',   'template' => 'EuroMillions\web\emailTemplates\WinEmailAboveTemplate'],
            ['lottery' => 'PowerBall',      'template' => 'EuroMillions\web\emailTemplates\WinEmailPowerBallAboveTemplate'],
            ['lottery' => 'MegaMillions',   'template' => 'EuroMillions\megamillions\emailTemplates\WinEmailMegaMillionsAboveTemplate'],
            ['lottery' => 'EuroJackpot',    'template' => 'EuroMillions\eurojackpot\emailTemplates\WinEmailEuroJackpotAboveTemplate'],
        ];
    }
}
