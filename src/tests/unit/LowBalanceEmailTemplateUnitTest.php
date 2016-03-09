<?php


namespace EuroMillions\tests\unit;


use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\emailTemplates\LowBalanceEmailTemplate;
use Money\Currency;
use Money\Money;
use Prophecy\Argument;
use EuroMillions\tests\base\UnitTestBase;

class LowBalanceEmailTemplateUnitTest extends UnitTestBase
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
        $this->lotteriesDataService->getNextJackpot('EuroMillions')->willReturn(new Money(10000,new Currency('EUR')));
        $this->lotteriesDataService->getNextDateDrawByLottery('EuroMillions')->willReturn(new \DateTime());


        $next_draw_day = new \DateTime();
        $emailTemplateDataStrategy_double = $this->getInterfaceWebDouble('IEmailTemplateDataStrategy');
        $emailTemplateDataStrategy_double->getData(Argument::type('EuroMillions\web\interfaces\IEmailTemplateDataStrategy'))->willReturn([]);
        $data = [
            'jackpot_amount' => new Money(10000, new Currency('EUR')),
            'draw_day_format_one' => $next_draw_day->format('l'),
            'draw_day_format_two' => $next_draw_day->format('j F Y')
        ];
        $emailTemplateDataStrategy_double->getData($emailTemplateDataStrategy_double->reveal())->willReturn($data);
        $sut = new LowBalanceEmailTemplate($emailTemplate, $emailTemplateDataStrategy_double->reveal());
        $actual = $sut->loadVars($emailTemplateDataStrategy_double->reveal());
        $this->assertEquals($expected,$actual);

    }

    private function getArrayContentTemplate()
    {
        $next_draw_day = new \DateTime();
        $jackpot= new Money(10000, new Currency('EUR'));
        $draw_day_format_one = $next_draw_day->format('l');
        $draw_day_format_two = $next_draw_day->format('j F Y');

        $vars = [
            'template' => 'low-balance',
            'subject' => 'Low balance',
            'vars' =>
                [
                    [
                        'name'    => 'jackpot',
                        'content' => number_format((float) $jackpot->getAmount() / 100,2,".",",")
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
                        'name' => 'url_add_funds',
                        'content' => 'https://localhost/account/wallet'
                    ]
                ]
        ];

        return $vars;
    }
}