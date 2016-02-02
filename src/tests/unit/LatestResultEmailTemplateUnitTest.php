<?php


namespace tests\unit;


use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\emailTemplates\LatestResultsEmailTemplate;
use EuroMillions\web\services\email_templates_strategies\DataLotteryEmailTemplateStrategy;
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
        $this->lotteriesDataService->getNextDateDrawByLottery('EuroMillions')->willReturn(new \DateTime('2016-02-02 20:00:00'));
        $this->lotteriesDataService->getNextJackpot('EuroMillions')->willReturn(new Money(10000,new Currency('EUR')));
        $sut = new LatestResultsEmailTemplate($emailTemplate,new DataLotteryEmailTemplateStrategy($this->lotteriesDataService->reveal()));
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

        //vars email template
        $vars = [
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
                        'content' => number_format((float) $jackpot->getAmount() / 100,2,".",",")
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