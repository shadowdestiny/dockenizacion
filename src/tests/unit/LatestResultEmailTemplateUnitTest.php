<?php


namespace EuroMillions\tests\unit;


use antonienko\MoneyFormatter\MoneyFormatter;
use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\emailTemplates\LatestResultsEmailTemplate;
use EuroMillions\web\vo\dto\EuroMillionsDrawBreakDownDTO;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;
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
        $emailTemplate = new EmailTemplate();
        $regular_numbers = [1, 2, 3, 4, 5];
        $lucky_numbers = [5, 8];
        $draw_result['regular_numbers'] = $regular_numbers;
        $draw_result['lucky_numbers'] = $lucky_numbers;
        $date_draw = new \DateTime('2016-05-23');
        $this->lotteryService_double->getLastResult('EuroMillions')->willReturn($draw_result);
        $this->lotteryService_double->getLastDrawDate('EuroMillions')->willReturn($date_draw);
        $emailDataStrategy_double = $this->getInterfaceWebDouble('IEmailTemplateDataStrategy');
        $data = [
          'draw_result' => $draw_result,
          'jackpot_amount' => '100.00',
          'last_draw_date' => $date_draw,
          'draw_day_format_one' => '',
          'draw_day_format_two' => '',
          'break_down' => '',
          'regular_numbers' => '',
          'lucky_numbers' => ''
        ];
        $emailDataStrategy_double->getData($emailDataStrategy_double->reveal())->willReturn($data);
        $sut = new LatestResultsEmailTemplate($emailTemplate, $emailDataStrategy_double->reveal());
        $break_down_list = new EuroMillionsDrawBreakDownDTO(new EuroMillionsDrawBreakDown($this->getBreakDownDataDraw()));
        $sut->setBreakDownList($break_down_list);
        $expected = $this->getArrayContentTemplate($this->getBreakDownList($break_down_list));
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
        $break_down_list = new EuroMillionsDrawBreakDownDTO(new EuroMillionsDrawBreakDown($this->getBreakDownDataDraw()));
        $propArray = $this->getArrayContentTemplate($this->getBreakDownList($break_down_list));

        $regular_numbers = [1, 2, 3, 4, 5];
        $lucky_numbers = [5, 8];
        $draw_result['regular_numbers'] = $regular_numbers;
        $draw_result['lucky_numbers'] = $lucky_numbers;
        $date_draw = new \DateTime('2016-05-23');
        $emailTemplate = new EmailTemplate();
        $emailDataStrategy_double = $this->getInterfaceWebDouble('IEmailTemplateDataStrategy');
        $data = [
            'draw_result' => $draw_result,
            'jackpot_amount' => new Money(10000,new Currency('EUR')),
            'last_draw_date' => $date_draw
        ];

        $obj = new \stdClass();
        $obj->draw_date = '23 May 2016';
        $obj->regular_numbers = $this->mapNumbers($regular_numbers);
        $obj->lucky_numbers = $this->mapNumbers($lucky_numbers);
        $obj->breakdown = $this->getBreakDownList($break_down_list);
        $obj->draw_day_format_one = '';
        $obj->draw_day_format_two = '';
        $obj->jackpot_amount = '100.00';
        $obj->date_header = '24 May 2016';

        $emailDataStrategy_double->getData($emailDataStrategy_double->reveal())->willReturn($data);
        $sut = new LatestResultsEmailTemplate($emailTemplate, $emailDataStrategy_double->reveal());
        $expected = $obj;
        $actual = $sut->loadVarsAsObject($propArray['vars']);
        $this->assertEquals($expected,$actual);
    }

    private function getArrayContentTemplate($breakDown)
    {
        $regular_numbers = [1, 2, 3, 4, 5];
        $lucky_numbers = [5, 8];
        $draw_result['regular_numbers'] = $regular_numbers;
        $draw_result['lucky_numbers'] = $lucky_numbers;
        $jackpot  = new Money(10000,new Currency('EUR'));
        $next_draw_day = new \DateTime('2016-05-23');
        $last_draw_date = $next_draw_day->format('j F Y');

        //vars email template
        $vars = [
            'template' => '624601',
            'subject' => 'Latest results',
            'vars' =>
                [
                    [
                        'name'    => 'breakdown',
                        'content' => $breakDown
                    ],
                    [
                        'name'    => 'jackpot_amount',
                        'content' => number_format((float) $jackpot->getAmount() / 100,2,".",",")
                    ],
                    [
                        'name'    => 'draw_date',
                        'content' => $last_draw_date
                    ],
                    [
                        'name'    => 'regular_numbers',
                        'content' => $this->mapNumbers($draw_result['regular_numbers'])
                    ],
                    [
                        'name'    => 'lucky_numbers',
                        'content' => $this->mapNumbers($draw_result['lucky_numbers'])
                    ],
                    [
                        'name'    => 'draw_day_format_one',
                        'content' => ''
                    ],
                    [
                        'name'    => 'draw_day_format_two',
                        'content' => ''
                    ],
                ]
        ];

        return $vars;
    }

    /**
     */
    public function getBreakDownList($break_down)
    {
        $euromillionsBreakDownDTO = $break_down;
        $moneyFormatter = new MoneyFormatter();
       // return $moneyFormatter->toStringByLocale('en_US', new Money((int) $amount, new Currency('EUR')));
        $break = [
            [
                'ball5' => '1',
                'star2' => '1',
                'winners' => $euromillionsBreakDownDTO->category_one->winners,
                'lottery_prize' => $moneyFormatter->toStringByLocale('en_US',new Money((int) $euromillionsBreakDownDTO->category_one->lottery_prize, new Currency('EUR')))
            ],
            [
                'ball5' => '1',
                'star1' => '1',
                'winners' => $euromillionsBreakDownDTO->category_two->winners,
                'lottery_prize' => $moneyFormatter->toStringByLocale('en_US',new Money((int) $euromillionsBreakDownDTO->category_two->lottery_prize, new Currency('EUR')))
            ],
            [
                'ball4' => '1',
                'star2' => '1',
                'winners' => $euromillionsBreakDownDTO->category_three->winners,
                'lottery_prize' => $moneyFormatter->toStringByLocale('en_US',new Money((int) $euromillionsBreakDownDTO->category_three->lottery_prize, new Currency('EUR')))
            ],
            [
                'ball4' => '1',
                'star1' => '1',
                'winners' => $euromillionsBreakDownDTO->category_four->winners,
                'lottery_prize' => $moneyFormatter->toStringByLocale('en_US',new Money((int) $euromillionsBreakDownDTO->category_four->lottery_prize, new Currency('EUR')))
            ],
            [
                'ball4' => '1',
                'star0' => '1',
                'winners' => $euromillionsBreakDownDTO->category_five->winners,
                'lottery_prize' => $moneyFormatter->toStringByLocale('en_US',new Money((int) $euromillionsBreakDownDTO->category_five->lottery_prize, new Currency('EUR')))
            ],
        ];

        return $break;
    }

    public function mapNumbers(array $numbers)
    {
        $numbersToEmail = [];

        foreach($numbers as $number) {
            $numbersToEmail[]['number'] = (int) $number;
        }
        return $numbersToEmail;
    }

    protected function getBreakDownDataDraw()
    {
        return [
                'category_one' => ['5 + 2', '189080000', '0'],
                'category_two' => ['5 + 1', '2939257', '9'],
                'category_three' => ['5 + 0', '8817797', '10'],
                'category_four' => ['4 + 2', '668015', '66'],
                'category_five' => ['4 + 1', '27516', '1.402'],
                'category_six' => ['4 + 0', '13149', '2.934'],
                'category_seven' => ['3 + 2', '6087', '4.527'],
                'category_eight' => ['2 + 2', '1893', '66.973'],
                'category_nine' => ['3 + 1', '1673', '72.488'],
                'category_ten' => ['3 + 0', '1341', '152.009'],
                'category_eleven' => ['1 + 2', '998', '358.960'],
                'category_twelve' => ['2 + 1', '852', '1.138.617'],
                'category_thirteen' => ['2 + 0', '415', '2.390.942'],
        ];
    }

}