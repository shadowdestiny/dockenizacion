<?php


namespace tests\unit;


use EuroMillions\shared\vo\Wallet;
use EuroMillions\web\components\NullPasswordHasher;
use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\emailTemplates\WinEmailTemplate;
use EuroMillions\web\entities\User;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\Password;
use EuroMillions\web\vo\UserId;
use Money\Currency;
use Money\Money;
use tests\base\UnitTestBase;


class WinEmailTemplateUnitTest extends UnitTestBase
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
        $result_amount = new Money(10000, new Currency('EUR'));
        $emailTemplate = new EmailTemplate();
        $sut = new WinEmailTemplate($emailTemplate,$this->lotteriesDataService->reveal());
        $sut->setUser($this->getUser());
        $sut->setResultAmount($result_amount);
        $actual = $sut->loadVars();
        $this->assertEquals($expected,$actual);
    }

    private function getArrayContentTemplate()
    {
        $vars = [
            'template' => 'win-email',
            'subject' => 'Congratulations',
            'vars' =>
                [
                    [
                        'name'    => 'user_name',
                        'content' => 'test'
                    ],
                    [
                        'name'    => 'winning',
                        'content' => 100.00
                    ],
                    [
                        'name'    => 'url_play',
                        'content' => 'localhost:443/play'
                    ],
                    [
                        'name'    => 'url_account',
                        'content' => 'localhost:443/account/wallet'
                    ]
                ]
        ];

        return $vars;
    }

    /**
     * @param string $currency
     * @return User
     */
    private function getUser($currency = 'EUR')
    {
        $user = new User();
        $user->initialize(
            [
                'id'               => new UserId('9098299B-14AC-4124-8DB0-19571EDABE55'),
                'name'             => 'test',
                'surname'          => 'test01',
                'email'            => new Email('raul.mesa@panamedia.net'),
                'password'         => new Password('passworD01', new NullPasswordHasher()),
                'validated'        => false,
                'wallet'          => new Wallet(new Money(50000, new Currency($currency))),
                'validation_token' => '33e4e6a08f82abb38566fc3bb8e8ef0d'
            ]
        );
        return $user;
    }
}