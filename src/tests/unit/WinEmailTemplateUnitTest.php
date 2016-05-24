<?php


namespace EuroMillions\tests\unit;


use EuroMillions\shared\vo\Wallet;
use EuroMillions\web\components\NullPasswordHasher;
use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\emailTemplates\WinEmailTemplate;
use EuroMillions\web\entities\User;
use EuroMillions\web\services\email_templates_strategies\NullEmailTemplateDataStrategy;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\Password;
use Money\Currency;
use Money\Money;
use EuroMillions\tests\base\UnitTestBase;


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
        $sut = new WinEmailTemplate($emailTemplate, new NullEmailTemplateDataStrategy());
        $sut->setUser($this->getUser());
        $sut->setResultAmount($result_amount);
        $sut->setWinningLine('1,2,3,4,5 (1,2)');
        $sut->setNummBalls(1);
        $sut->setStarBalls(2);
        $actual = $sut->loadVars();
        $this->assertEquals($expected,$actual);
    }

    private function getArrayContentTemplate()
    {
        $vars = [
            'template' => '625142',
            'subject' => 'Congratulations',
            'vars' =>
                [
                    [
                        'name'    => 'winning_line',
                        'content' => '1,2,3,4,5 (1,2)'
                    ],
                    [
                        'name'    => 'num_balls',
                        'content' => '1'
                    ],
                    [
                        'name'    => 'star_balls',
                        'content' => '2'
                    ],
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
                        'content' => 'https://localhost/play'
                    ],
                    [
                        'name'    => 'url_account',
                        'content' => 'https://localhost/account/wallet'
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
                'id'               => '9098299B-14AC-4124-8DB0-19571EDABE55',
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