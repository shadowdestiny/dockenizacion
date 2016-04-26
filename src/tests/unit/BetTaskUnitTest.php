<?php
namespace EuroMillions\tests\unit;

use EuroMillions\shared\config\Namespaces;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\entities\Notification;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\entities\UserNotifications;
use EuroMillions\web\tasks\BetTask;
use EuroMillions\web\vo\NotificationValue;
use EuroMillions\shared\vo\results\ActionResult;
use Money\Currency;
use Money\Money;
use Prophecy\Argument;
use EuroMillions\tests\base\EuroMillionsResultRelatedTest;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\UserMother;



//EMTD change bet to betservice_double and call to validate method
class BetTaskUnitTest extends UnitTestBase
{

    use EuroMillionsResultRelatedTest;

    private $playConfigRepository_double;

    private $euroMillionsDrawRepository_double;

    private $lotteryDrawRepository_double;

    private $lotteryService_double;

    private $betRepository_double;

    private $userRepository_double;

    private $playService_double;

    private $emailService_double;

    private $userService_double;

    private $time_to_retry;

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
        $this->lotteryService_double = $this->getServiceDouble('LotteryService');
        $this->playService_double = $this->getServiceDouble('PlayService');
        $this->emailService_double = $this->getServiceDouble('EmailService');
        $this->userService_double = $this->getServiceDouble('UserService');
        $this->time_to_retry = 1403738800;
        parent::setUp();
    }


    public function getDatesAndPlayConfigsForCreateBet()
    {
        return [
            ["2015-10-05", 2, "2015-10-06"],
            ["2015-10-07", 3, "2015-10-09"],
        ];
    }


    /**
     * method placeBets
     * when called
     * should callPlaceBet
     */
    public function test_placeBets_called_callPlaceBet()
    {
        $lottery = $this->prepareLotteryEntity('EuroMillions');
        $args[0] = '2016-04-05';
        $this->lotteryService_double->getLotteriesOrderedByNextDrawDate()->willReturn([$lottery]);
        $this->lotteryService_double->placeBetForNextDraw($lottery,new \DateTime($args[0]))->shouldBeCalled();
        $sut = new BetTask();
        $sut->initialize($this->lotteryService_double->reveal(), $this->playService_double->reveal(),$this->emailService_double->reveal(), $this->userService_double->reveal());
        $sut->placeBetsAction($args);
    }

    /**
     * method placeBets
     * when calledBeforeCloseTime
     * should callPlaceBet
     */
    public function test_placeBets_calledBeforeCloseTime_callPlaceBet()
    {

    }

    /**
     * method longTermNotificationAction
     * when calledThreeDaysBeforeLastDrawFromMySuscription
     * should sendEmailNotification
     */
    public function test_longTermNotificationAction_calledThreeDaysBeforeLastDrawFromMySuscription_sendEmailNotification()
    {

        $today = new \DateTime('2015-12-04 00:00:00');
        $result_play_config = $this->getPlayConfigList($this->getUser());
        $this->playService_double->getPlayConfigWithLongEnded($today)->willReturn($result_play_config);
        $this->userService_double->checkLongTermAndSendNotification($result_play_config->getValues(),$today)->shouldBeCalled();
        $sut = new BetTask();
        $sut->initialize($this->lotteryService_double->reveal(), $this->playService_double->reveal(),$this->emailService_double->reveal(), $this->userService_double->reveal());
        $sut->longTermNotificationAction($today);
    }


    private function getUser()
    {
        return UserMother::aUserWith50Eur()
            ->build();
    }

    /**
     * @param $lottery_name
     * @return Lottery
     */
    protected function prepareLotteryEntity($lottery_name)
    {
        $lottery = new Lottery();
        $lottery->initialize([
            'id'        => 1,
            'name'      => $lottery_name,
            'active'    => 1,
            'frequency' => 'w0100100',
            'draw_time' => '20:00:00',
            'single_bet_price' => new Money(250, new Currency('EUR')),
        ]);
        return $lottery;
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


    private function getPlayConfigList($user)
    {

        $attributes_list = [
            [
                'active'        => 1,
                'startDrawDate' => new \DateTime('2015-10-05'),
                'lastDrawDate'  => new \DateTime('2015-12-03'),
                'user'          => $user
            ],
            [
                'active'        => 1,
                'startDrawDate' => new \DateTime('2015-10-05'),
                'lastDrawDate'  => new \DateTime('2015-12-03'),
                'user'          => $user
            ],
            [
                'active'        => 1,
                'startDrawDate' => new \DateTime('2015-10-07'),
                'lastDrawDate'  => new \DateTime('2015-12-03'),
                'user'          => $user
            ],
            [
                'active'        => 1,
                'startDrawDate' => new \DateTime('2015-10-05'),
                'lastDrawDate'  => new \DateTime('2015-12-03'),
                'user'          => $user
            ]
        ];
        $play_config_list = [];
        foreach($attributes_list as $attributes) {
            $play_config_list[] = $this->getPlayConfigFromAttributes($attributes);
        }
        return new ActionResult(true, $play_config_list);
    }

    private function getUserNotifications()
    {
        $userNotifications = new UserNotifications();
        $userNotifications->setUser($this->getUser());
        $notification = new Notification();
        $notification->setDescription('Test');
        $userNotifications->setNotification($notification);
        $notificationType = new NotificationValue(1,3500000);
        $userNotifications->setConfigValue($notificationType);
        $userNotifications->setActive(true);
        return $userNotifications;
    }
}