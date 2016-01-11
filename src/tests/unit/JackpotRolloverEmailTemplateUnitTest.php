<?php


namespace tests\unit;


use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\emailTemplates\JackpotRolloverEmailTemplate;
use Money\Currency;
use Money\Money;
use tests\base\UnitTestBase;

class JackpotRolloverEmailTemplateUnitTest extends UnitTestBase
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
        $expected = $this->getArrayContentTemplateJackpot();
        $emailTemplate = new EmailTemplate();
        $this->lotteriesDataService->getNextDateDrawByLottery('EuroMillions')->willReturn(new \DateTime());
        $this->lotteriesDataService->getNextJackpot('EuroMillions')->willReturn(new Money(10000,new Currency('EUR')));
        $sut = new JackpotRolloverEmailTemplate($emailTemplate,$this->lotteriesDataService->reveal());
        $actual = $sut->loadVars();
        $this->assertEquals($expected,$actual);

    }

    private function getArrayContentTemplateJackpot()
    {
        $next_draw_day = new \DateTime();
        $jackpot_amount = new Money(10000, new Currency('EUR'));
        $draw_day_format_one = $next_draw_day->format('l');
        $draw_day_format_two = $next_draw_day->format('j F Y');

        return $vars = [
            'template'=> 'jackpot-rollover',
            'subject' => 'Jackpot',
            'vars' =>
                [
                    [
                        'name'    => 'jackpot',
                        'content' => number_format((float) $jackpot_amount->getAmount() / 100,2,".",",")
                    ],
                    [
                        'name'    => 'draw_day_format_one',
                        'content' => $draw_day_format_one
                    ],
                    [
                        'name'    => 'draw_day_format_two',
                        'content' => $draw_day_format_two,
                    ],
                    [
                        'name'    => 'time_closed',
                        'content' =>  '19:00 CET'
                    ],
                    [
                        'name'    => 'url_play',
                        'content' => 'localhost:4433/play'
                    ]
                ]
        ];
    }

}