<?php


namespace EuroMillions\tests\unit;


use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\emailTemplates\LatestResultsEmailTemplate;
use Money\Currency;
use Money\Money;
use Prophecy\Argument;
use EuroMillions\tests\base\UnitTestBase;

class LatestResultEmailTemplateUnitTest extends UnitTestBase
{


    protected $lotteryService_double;

    public function setUp()
    {
        parent::setUp();
        $this->lotteryService_double = $this->getServiceDouble('LotteryService');
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
        $date_draw = new \DateTime('2016-05-20');
        $this->lotteryService_double->getLastResult('EuroMillions')->willReturn($draw_result);
        $this->lotteryService_double->getLastDrawDate('EuroMillions')->willReturn($date_draw);
        $emailDataStrategy_double = $this->getInterfaceWebDouble('IEmailTemplateDataStrategy');
        $data = [
          'draw_result' => $draw_result,
          'jackpot_amount' => new Money(10000,new Currency('EUR')),
          'last_draw_date' => $date_draw
        ];
        $emailDataStrategy_double->getData($emailDataStrategy_double->reveal())->willReturn($data);
        $sut = new LatestResultsEmailTemplate($emailTemplate, $emailDataStrategy_double->reveal());
        $sut->setBreakDownList('');
        $actual = $sut->loadVars($emailDataStrategy_double->reveal());
        $this->assertEquals($expected,$actual);
    }

    /**
     * method loadVarsAsObject
     * when called
     * should returnProperProperties
     */
    public function test_loadVarsAsObject_called_returnProperProperties()
    {
        $propArray = $this->getArrayContentTemplate();

        $regular_numbers = [1, 2, 3, 4, 5];
        $lucky_numbers = [5, 8];
        $draw_result['regular_numbers'] = $regular_numbers;
        $draw_result['lucky_numbers'] = $lucky_numbers;
        $date_draw = new \DateTime('2016-05-20');
        $emailTemplate = new EmailTemplate();
        $emailDataStrategy_double = $this->getInterfaceWebDouble('IEmailTemplateDataStrategy');
        $data = [
            'draw_result' => $draw_result,
            'jackpot_amount' => new Money(10000,new Currency('EUR')),
            'last_draw_date' => $date_draw
        ];

        $obj = new \stdClass();
        $obj->jackpot = '100.00';
        $obj->draw_date = '20 May 2016';
        $obj->regular_numbers = json_encode($regular_numbers);
        $obj->lucky_numbers = json_encode($lucky_numbers);
        $obj->breakdown = '';//json_encode([['test' => 'test', 'test2' => 'test2']]);

        $emailDataStrategy_double->getData($emailDataStrategy_double->reveal())->willReturn($data);
        $sut = new LatestResultsEmailTemplate($emailTemplate, $emailDataStrategy_double->reveal());


        $expected = $obj;
        $actual = $sut->loadVarsAsObject($propArray['vars']);
        $this->assertEquals($expected,$actual);
    }

    private function getArrayContentTemplate()
    {
        $regular_numbers = [1, 2, 3, 4, 5];
        $lucky_numbers = [5, 8];
        $draw_result['regular_numbers'] = $regular_numbers;
        $draw_result['lucky_numbers'] = $lucky_numbers;
        $jackpot  = new Money(10000,new Currency('EUR'));
        $next_draw_day = new \DateTime('2016-05-20');
        $last_draw_date = $next_draw_day->format('j F Y');

        //vars email template
        $vars = [
            'template' => '624601',
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