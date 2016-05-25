<?php


namespace EuroMillions\tests\unit;


use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\OrderMother;
use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\emailTemplates\PurchaseConfirmationEmailTemplate;
use EuroMillions\web\entities\PlayConfig;

class PurchaseConfirmationEmailTemplateUnitTest extends UnitTestBase
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
        $order = OrderMother::aJustOrder()->build();
        $user = UserMother::aUserWith50Eur()->build();
        $expected = $this->getArrayContentTemplate($user, $order->getPlayConfig());
        $emailTemplate = new EmailTemplate();
        $emailTemplateDataStrategy_double = $this->getInterfaceWebDouble('IEmailTemplateDataStrategy');
        $sut = new PurchaseConfirmationEmailTemplate($emailTemplate, $emailTemplateDataStrategy_double->reveal());
        $sut->setUser($user);
        $sut->setLine($order->getPlayConfig());
        $actual = $sut->loadVars();
        $this->assertEquals($expected,$actual);
    }



    private function getArrayContentTemplate($user, $playConfigs)
    {
        $vars = [
            'template' => '624539',
            'subject' => 'Congratulations',
            'vars' =>
                [
                    [
                        'name' => 'line',
                        'content' => $this->getLine($playConfigs),
                    ],
                    [
                        'name'    => 'user_name',
                        'content' => $user->getName()
                    ],
                    [
                        'name' => 'draw_day_format_one',
                        'content' => ''
                    ],
                    [
                        'name' => 'draw_day_format_two',
                        'content' => ''
                    ]
                ]
        ];

        return $vars;
    }


    /**
     * @return mixed
     */
    public function getLine($playConfigs)
    {
        $lines = [];
        /** @var PlayConfig $line */
        foreach($playConfigs as $line) {
            $play = [];
            foreach($line->getLine()->getRegularNumbersArray() as $balls) {
                $play['regular_numbers'][]['number'] = $balls;
            }
            foreach($line->getLine()->getLuckyNumbersArray() as $stars) {
                $play['lucky_numbers'][]['number'] = $stars;
            }
            array_push($lines,$play);
        }
        return $lines;
    }


}