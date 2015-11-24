<?php


namespace tests\unit;


use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\emailTemplates\LatestResultsEmailTemplate;
use Money\Currency;
use Money\Money;
use Prophecy\Argument;
use tests\base\UnitTestBase;

class LatestResultEmailTemplateUnitTest extends UnitTestBase
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
        $regular_numbers = [1, 2, 3, 4, 5];
        $lucky_numbers = [5, 8];
        $draw_result['regular_numbers'] = $regular_numbers;
        $draw_result['lucky_numbers'] = $lucky_numbers;
        $date_draw = new \DateTime();
        $this->lotteriesDataService->getLastResult('EuroMillions')->willReturn($draw_result);
        $this->lotteriesDataService->getLastJackpot('EuroMillions')->willReturn(new Money(10000,new Currency('EUR')));
        $this->lotteriesDataService->getLastDrawDate('EuroMillions')->willReturn($date_draw);
        $sut = new LatestResultsEmailTemplate($emailTemplate,$this->lotteriesDataService->reveal());
        $sut->setBreakDownList('');
        $actual = $sut->loadVars();
        $this->assertEquals($expected,$actual);
    }

    private function getArrayContentTemplate()
    {
        $regular_numbers = [1, 2, 3, 4, 5];
        $lucky_numbers = [5, 8];
        $draw_result['regular_numbers'] = $regular_numbers;
        $draw_result['lucky_numbers'] = $lucky_numbers;
        $jackpot  = new Money(10000,new Currency('EUR'));
        $next_draw_day = new \DateTime();
        $last_draw_date = $next_draw_day->format('j F Y');
        $date = new \DateTime();

        //vars email template
        $vars = [
            'header' => $date->format('j M Y'),
            'template' => 'latest-results',
            'subject' => 'Latest results',
            'vars' =>
                [
                    [
                        'name'    => 'breakdown',
                        'content' => ''
                    ],
                    [
                        'name'    => 'jackpot',
                        'content' => $jackpot->getAmount()/100
                    ],
                    [
                        'name'    => 'draw_date',
                        'content' => $last_draw_date
                    ],
                    [
                        'name'    => 'regular_numbers',
                        'content' => $draw_result['regular_numbers']
                    ],
                    [
                        'name'    => 'lucky_numbers',
                        'content' => $draw_result['lucky_numbers']
                    ]
                ]
        ];

        return $vars;
    }

}