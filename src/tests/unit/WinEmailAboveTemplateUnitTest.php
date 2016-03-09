<?php


namespace EuroMillions\tests\unit;


use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\emailTemplates\WinEmailAboveTemplate;
use Money\Currency;
use Money\Money;
use Prophecy\Argument;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\UserMother;

class WinEmailAboveTemplateUnitTest extends UnitTestBase
{

    protected $lotteriesDataService;
    protected $currencyConversionService_double;


    public function setUp()
    {
        parent::setUp();
        $this->lotteriesDataService = $this->getServiceDouble('LotteriesDataService');
        $this->currencyConversionService_double = $this->getServiceDouble('CurrencyConversionService');

    }

    /**
     * method loadVars
     * when called
     * should returnArrayWithProperData
     */
    public function test_loadVars_called_returnArrayWithProperData()
    {
        $expected = $this->getArrayContentTemplate();
        $result_amount = new Money(10000, new Currency('EUR'));
        $emailTemplate = new EmailTemplate();
        $user = UserMother::aUserWith50Eur()->build();
        $user->setUserCurrency(new Currency('USD'));
        $emailTemplateDataStrategy_double = $this->getInterfaceWebDouble('IEmailTemplateDataStrategy');
        $data = [
            'amount_converted' => '$2'
        ];
        $emailTemplateDataStrategy_double->getData()->willReturn($data);
        $user_currency = new Currency('USD');
        $expected_result = new Money(1, new Currency('USD'));
        $this->currencyConversionService_double->convert(Argument::any(),$user_currency)->willReturn($expected_result);
        $this->currencyConversionService_double->toString($expected_result, $user_currency)->willReturn('$2');
        $emailTemplateDataStrategy_double->getData($emailTemplateDataStrategy_double->reveal())->willReturn($data);
        $sut = new WinEmailAboveTemplate($emailTemplate, $emailTemplateDataStrategy_double->reveal());
        $sut->setResultAmount($result_amount);
        $sut->setUser($user);
        $actual = $sut->loadVars();
        $this->assertEquals($expected,$actual);
    }



    private function getArrayContentTemplate()
    {
        $vars = [
            'template' => 'win-email-above-1500',
            'subject' => 'Congratulations',
            'vars' =>
                [
                    [
                        'name'    => 'user_name',
                        'content' => 'Antonio'
                    ],
                    [
                        'name'    => 'winning',
                        'content' => 100.00
                    ],
                    [
                        'name' => 'amount_converted',
                        'content' => '$2'
                    ]
                ]
        ];

        return $vars;
    }

}