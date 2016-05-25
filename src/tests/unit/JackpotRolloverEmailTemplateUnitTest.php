<?php


namespace EuroMillions\tests\unit;


use EuroMillions\shared\vo\Wallet;
use EuroMillions\web\components\NullPasswordHasher;
use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\emailTemplates\JackpotRolloverEmailTemplate;
use EuroMillions\web\entities\User;
use EuroMillions\web\services\email_templates_strategies\JackpotDataEmailTemplateStrategy;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\Password;
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
        $this->lotteryService->getNextJackpot('EuroMillions')->willReturn(new Money((int) 10000, new Currency('EUR')));
        $sut = new JackpotRolloverEmailTemplate($emailTemplate, new JackpotDataEmailTemplateStrategy($this->lotteryService->reveal()) );
        $sut->setUser($this->getUser());
        $sut->setThresholdAmount(1000000);
        $actual = $sut->loadVars();
        $this->assertEquals($expected,$actual);
    }

    private function getArrayContentTemplateJackpot()
    {
        $next_draw_day = new \DateTime();
        $jackpot_amount = '€100';
        $draw_day_format_one = $next_draw_day->format('l');
        $draw_day_format_two = $next_draw_day->format('j F Y');

        return $vars = [
            'template'=> '625301',
            'subject' => 'The Jackpot has reached your threshold',
            'vars' =>
                [
                    [
                        'name' => 'user_name',
                        'content' => 'test'
                    ],
                    [
                        'name' => 'player_alert_threshold',
                        'content' => '10,000.00'
                    ],
                    [
                        'name'    => 'current_jackpot',
                        'content' => $jackpot_amount
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
                'wallet'           => new Wallet(new Money(5000, new Currency($currency))),
                'validation_token' => '33e4e6a08f82abb38566fc3bb8e8ef0d'
            ]
        );
        return $user;
    }

}