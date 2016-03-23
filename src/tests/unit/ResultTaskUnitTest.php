<?php


namespace EuroMillions\tests\unit;


use EuroMillions\shared\vo\Wallet;
use EuroMillions\web\components\NullPasswordHasher;
use EuroMillions\shared\config\Namespaces;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\Notification;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\entities\UserNotifications;
use EuroMillions\web\tasks\ResultTask;
use EuroMillions\web\vo\DrawDays;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;
use EuroMillions\web\vo\NotificationValue;
use EuroMillions\web\vo\Password;
use EuroMillions\shared\vo\results\ActionResult;
use Money\Currency;
use Money\Money;
use Prophecy\Argument;
use EuroMillions\tests\base\UnitTestBase;

class ResultTaskUnitTest extends UnitTestBase
{

    private $playConfigRepository_double;

    private $euroMillionsDrawRepository_double;

    private $lotteryDrawRepository_double;

    private $lotteriesDataService_double;

    private $betRepository_double;

    private $userRepository_double;

    private $playService_double;

    private $emailService_double;

    private $userService_double;

    private $currencyService_double;

    protected function getEntityManagerStubExtraMappings()
    {
        return [
            Namespaces::ENTITIES_NS . 'PlayConfig' => $this->playConfigRepository_double,
            Namespaces::ENTITIES_NS . 'EuroMillionsDraw' => $this->euroMillionsDrawRepository_double,
            Namespaces::ENTITIES_NS . 'Lottery' => $this->lotteryDrawRepository_double,
            Namespaces::ENTITIES_NS . 'Bet' => $this->betRepository_double,
            Namespaces::ENTITIES_NS . 'User' => $this->userRepository_double,
        ];
    }

    public function setUp()
    {
        $this->lotteriesDataService_double = $this->getServiceDouble('LotteriesDataService');
        $this->playService_double = $this->getServiceDouble('PlayService');
        $this->emailService_double = $this->getServiceDouble('EmailService');
        $this->userService_double = $this->getServiceDouble('UserService');
        $this->currencyService_double = $this->getServiceDouble('CurrencyService');
        parent::setUp();
    }

    /**
     * method updateAction
     * when updateLastBreakDown
     * should sendEmailByEachUserInPlayConfig
     */
    public function test_updateAction_updateLastBreakDown_sendEmailByEachUserInPlayConfig()
    {
        $this->markTestIncomplete('Hay que rehacer este test, para que tenga en cuenta que debe devolver el draw CON la información del breakdown incluida');
        $lottery_name = 'EuroMillions';
        $today = new \DateTime('2015-10-10');
        $draw_result['regular_numbers'] = [];
        $draw_result['lucky_numbers'] = [];
        $play_config_list = $this->getPlayConfigList();
        $notificationType = new NotificationValue(4,0);
        $euromillionsDraw = new EuroMillionsDraw();
        $euromillionsDraw->setBreakDown(new EuroMillionsDrawBreakDown($this->getBreakDownDataDraw()));
        $this->lotteriesDataService_double->updateLastDrawResult('EuroMillions')->shouldBeCalled();
        $this->lotteriesDataService_double->updateLastBreakDown('EuroMillions')->shouldBeCalled();
        $this->playService_double->getPlaysConfigToBet($today)->willReturn($play_config_list);
        $this->userService_double->getUser('9098299B-14AC-4124-8DB0-19571EDABE55')->willReturn($this->getUser());
        $this->userService_double->getActiveNotificationsByUserAndType(Argument::any(),Argument::any())->willReturn(new ActionResult(true,$this->getUserNotifications($notificationType)));
        $this->emailService_double->sendTransactionalEmail($this->getUser(),Argument::type('EuroMillions\web\emailTemplates\IEmailTemplate'))->shouldBeCalledTimes(4);
        $this->userService_double->getActiveNotificationsByType(Argument::any())->willReturn(new ActionResult(true,$this->getUserNotifications($notificationType)));
        $sut = new ResultTask();
        $sut->initialize($this->lotteriesDataService_double->reveal(),
        $this->playService_double->reveal(),
        $this->emailService_double->reveal(), $this->userService_double->reveal(), $this->currencyService_double->reveal());
        $sut->updateAction($today);
    }

    private function getPlayConfigList()
    {

        $attributes_list = [
            [
                'active'        => 1,
                'startDrawDate' => new \DateTime('2015-10-05'),
                'lastDrawDate'  => new \DateTime('2015-12-03'),
                'draw_days'     => new DrawDays('25'),
                'user'          => $this->getUser()
            ],
            [
                'active'        => 1,
                'startDrawDate' => new \DateTime('2015-10-05'),
                'lastDrawDate'  => new \DateTime('2015-12-03'),
                'draw_days'     => new DrawDays('5'),
                'user'          => $this->getUser()
            ],
            [
                'active'        => 1,
                'startDrawDate' => new \DateTime('2015-10-07'),
                'lastDrawDate'  => new \DateTime('2015-12-03'),
                'draw_days'     => new DrawDays('5'),
                'user'          => $this->getUser()
            ],
            [
                'active'        => 1,
                'startDrawDate' => new \DateTime('2015-10-05'),
                'lastDrawDate'  => new \DateTime('2015-12-03'),
                'draw_days'     => new DrawDays('2'),
                'user'          => $this->getUser()
            ]
        ];
        $play_config_list = [];
        foreach($attributes_list as $attributes) {
            $play_config_list[] = $this->getPlayConfigFromAttributes($attributes);
        }
        return new ActionResult(true, $play_config_list);
    }

    /**
     * @param $attributes
     * @return PlayConfig
     */
    private function getPlayConfigFromAttributes($attributes)
    {
        $play_config1 = new PlayConfig();
        $play_config1->initialize($attributes);
        return $play_config1;
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
                'id' => '9098299B-14AC-4124-8DB0-19571EDABE55',
                'name'     => 'test',
                'surname'  => 'test01',
                'email'    => new Email('raul.mesa@panamedia.net'),
                'password' => new Password('passworD01', new NullPasswordHasher()),
                'validated' => false,
                'wallet' => new Wallet(new Money(5000,new Currency($currency))),
                'validation_token' => '33e4e6a08f82abb38566fc3bb8e8ef0d'
            ]
        );
        return $user;
    }

    protected function getBreakDownDataDraw()
    {
        return [
            [
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
            ]
        ];
    }


    private function getUserNotifications($notificationType)
    {
        $userNotifications = new UserNotifications();
        $userNotifications->setUser($this->getUser());
        $notification = new Notification();
        $notification->setDescription('Test');
        $userNotifications->setNotification($notification);
        $userNotifications->setConfigValue($notificationType);
        $userNotifications->setActive(true);
        return [$userNotifications];
    }
}