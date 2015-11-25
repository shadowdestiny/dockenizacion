<?php


namespace tests\unit;


use EuroMillions\web\emailTemplates\LongPlayEndedEmailTemplate;
use EuroMillions\web\emailTemplates\EmailTemplate;
use Money\Currency;
use Money\Money;
use tests\base\UnitTestBase;

class LongPlayEndedEmailTemplateUnitTest extends UnitTestBase
{

    protected $lotteriesDataService;

    public function setUp()
    {
        parent::setUp();
        $this->lotteriesDataService = $this->getServiceDouble('LotteriesDataService');
    }


    /**
     * method loadVars
     * when called
     * should returnArrayWithProperData
     */
    public function test_loadVars_called_returnArrayWithProperData()
    {
        $expected = $this->getArrayContentTemplate();
        $emailTemplate = new EmailTemplate();
        $this->lotteriesDataService->getNextJackpot('EuroMillions')->willReturn(new Money(10000, new Currency('EUR')));
        $sut = new LongPlayEndedEmailTemplate($emailTemplate,$this->lotteriesDataService->reveal());
        $actual = $sut->loadVars();
        $this->assertEquals($expected,$actual);
    }

    private function getArrayContentTemplate()
    {

        $jackpot = new Money(10000, new Currency('EUR'));
        $vars = [
            'template' => 'long-play-is-ended',
            'subject' => 'Your long play is ended',
            'vars' =>
                [
                    [
                        'name'    => 'jackpot',
                        'content' => $jackpot->getAmount() /100
                    ],
                    [
                        'name'    => 'url_play',
                        'content' => 'localhost:8080/play'
                    ]
                ]
        ];

        return $vars;
    }

}