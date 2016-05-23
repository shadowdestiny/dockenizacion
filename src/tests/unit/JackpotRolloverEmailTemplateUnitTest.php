<?php


namespace EuroMillions\tests\unit;


use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\emailTemplates\JackpotRolloverEmailTemplate;
use EuroMillions\web\services\email_templates_strategies\JackpotDataEmailTemplateStrategy;
use Money\Currency;
use Money\Money;
use EuroMillions\tests\base\UnitTestBase;

class JackpotRolloverEmailTemplateUnitTest extends UnitTestBase
{

    protected $lotteryService;

    public function setUp()
    {
        parent::setUp();
        $this->lotteryService = $this->getServiceDouble('LotteryService');
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
        $this->lotteryService->getNextDateDrawByLottery('EuroMillions')->willReturn(new \DateTime());
        $this->lotteryService->getNextJackpot('EuroMillions')->willReturn(new Money(10000,new Currency('EUR')));
        $sut = new JackpotRolloverEmailTemplate($emailTemplate, new JackpotDataEmailTemplateStrategy($this->lotteryService->reveal()) );
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
            'template'=> '625301',
            'subject' => 'The Jackpot has reached your threshold',
            'vars' =>
                [
                    [
                        'name'    => 'current_jackpot',
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
                        'content' => 'https://localhost/play'
                    ]
                ]
        ];
    }

}