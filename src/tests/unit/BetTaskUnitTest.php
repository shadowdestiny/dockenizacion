<?php
namespace EuroMillions\tests\unit;

use EuroMillions\shared\config\Namespaces;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\Notification;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\entities\UserNotifications;
use EuroMillions\web\exceptions\InvalidBalanceException;
use EuroMillions\web\tasks\BetTask;
use EuroMillions\web\vo\DrawDays;
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

    /**
     * method createBet
     * when called
     * should callPlayServiceBetOncePerEachEuromillionsDraw
     * @dataProvider getDatesAndPlayConfigsForCreateBet
     */
    public function test_createBet_called_callPlayServiceBetOncePerEachEuromillionsDraw($today, $callTimes, $lotteryDrawDate)
    {
        $euroMillionsDraw = $this->getEuroMillionsDraw($lotteryDrawDate);
        $this->lotteryService_double->getNextDrawByLottery('EuroMillions')->willReturn(new ActionResult(true,$euroMillionsDraw));
        $play_config_list = $this->getPlayConfigList($this->getUser());
        $this->playService_double->getPlaysConfigToBet($euroMillionsDraw->getDrawDate())->willReturn($play_config_list);
        $this->playService_double->bet(Argument::type('EuroMillions\web\entities\PlayConfig'), $euroMillionsDraw)->shouldBeCalledTimes($callTimes);
        $this->userService_double->getUser(Argument::any())->willReturn($this->getUser());
        $this->userService_double->getActiveNotificationsByUserAndType(Argument::any(),Argument::any())->willReturn(new ActionResult(true,$this->getUserNotifications()));
        $this->lotteryService_double->getNextJackpot('EuroMillions')->willReturn(new Money(10000,new Currency('EUR')));
        $this->lotteryService_double->getNextDateDrawByLottery('EuroMillions')->willReturn(new \DateTime());
        $sut = new BetTask();
        $sut->initialize($this->lotteryService_double->reveal(), $this->playService_double->reveal(), $this->emailService_double->reveal(), $this->userService_double->reveal());
        $today = new \DateTime($today);
        $sut->createBetAction($today, $this->time_to_retry);
     }

    public function getDatesAndPlayConfigsForCreateBet()
    {
        return [
            ["2015-10-05", 2, "2015-10-06"],
            ["2015-10-07", 3, "2015-10-09"],
        ];
    }

    /**
     * method createBet
     * when called
     * should sendProperArgumentsToEachCallOfPlayServiceBet
     */
    public function test_createBet_called_sendProperArgumentsToEachCallOfPlayServiceBet()
    {
        $euroMillionsDraw = $this->getEuroMillionsDraw('2015-10-09');
        $this->lotteryService_double->getNextDrawByLottery('EuroMillions')->willReturn(new ActionResult(true,$euroMillionsDraw));
        $play_config_list = $this->getPlayConfigList($this->getUser());
        $this->playService_double->getPlaysConfigToBet($euroMillionsDraw->getDrawDate())->willReturn($play_config_list);
        $play_config = $this->getPlayConfigList($this->getUser());

        /** @var array $play_config_values */
        $play_config_values = $play_config->getValues();
        $this->playService_double->bet($play_config_values[0], $euroMillionsDraw)->shouldBeCalled();
        $this->playService_double->bet($play_config_values[1], $euroMillionsDraw)->shouldBeCalled();
        $this->playService_double->bet($play_config_values[2], $euroMillionsDraw)->shouldBeCalled();

        $this->userService_double->getUser(Argument::any())->willReturn($this->getUser());
        $this->userService_double->getActiveNotificationsByUserAndType(Argument::any(),Argument::any())->willReturn(new ActionResult(true,$this->getUserNotifications()));

        $sut = new BetTask();
        $sut->initialize($this->lotteryService_double->reveal(), $this->playService_double->reveal(),$this->emailService_double->reveal(), $this->userService_double->reveal());
        $today = new \DateTime('2015-10-07');
        $sut->createBetAction($today, $this->time_to_retry);
    }

    /**
     * method createBet
     * when calledWithValidUserButWithLowBalance
     * should sendEmailWithEmptyBalance
     */
    public function test_createBet_calledWithValidUserButWithLowBalance_sendEmailWithEmptyBalance()
    {
        $euroMillionsDraw = $this->getEuroMillionsDraw('2015-10-16');

        $this->lotteryService_double->getNextDrawByLottery('EuroMillions')->willReturn(new ActionResult(true,$euroMillionsDraw));
        $play_config_list = $this->getPlayConfigList($this->getUser());
        $this->playService_double->getPlaysConfigToBet($euroMillionsDraw->getDrawDate())->willReturn($play_config_list);
        $this->playService_double->bet(Argument::type('EuroMillions\web\entities\PlayConfig'), $euroMillionsDraw)->shouldBeCalledTimes(1);
        $this->playService_double->bet(Argument::type('EuroMillions\web\entities\PlayConfig'), $euroMillionsDraw)->willThrow(new InvalidBalanceException());
        $this->userService_double->getUser(Argument::any())->willReturn($this->getUser());
        $this->userService_double->getActiveNotificationsByUserAndType(Argument::any(),Argument::any())->willReturn(new ActionResult(true,[$this->getUserNotifications()]));
        $this->lotteryService_double->getNextJackpot('EuroMillions')->willReturn(new Money(10000,new Currency('EUR')));
        $this->lotteryService_double->getNextDateDrawByLottery('EuroMillions')->willReturn(new \DateTime());
        $this->emailService_double->sendTransactionalEmail(Argument::type('EuroMillions\web\entities\User'),Argument::type('EuroMillions\web\emailTemplates\IEmailTemplate'))->shouldBeCalledTimes(1);
        $sut = new BetTask();
        $sut->initialize($this->lotteryService_double->reveal(),
            $this->playService_double->reveal(),$this->emailService_double->reveal(), $this->userService_double->reveal());
        $today = new \DateTime('2015-10-07');
        $sut->createBetAction($today, $this->time_to_retry);
    }


    /**
     * method createBetAction
     * when calledWithValidData
     * should returnServiceActionResultFalseWithoutResult
     */
    public function test_createBetAction_calledWithValidData_returnServiceActionResultFalseWithoutResult()
    {
        $euroMillionsDraw = $this->getEuroMillionsDraw('2015-10-09');
        $this->lotteryService_double->getNextDrawByLottery('EuroMillions')->willReturn(new ActionResult(true,$euroMillionsDraw));
        $this->playService_double->getPlaysConfigToBet($euroMillionsDraw->getDrawDate())->willReturn(new ActionResult(false));
        $this->playService_double->bet(Argument::any(),Argument::any())->shouldNotBeCalled();
        $sut = new BetTask();
        $sut->initialize($this->lotteryService_double->reveal(), $this->playService_double->reveal(),$this->emailService_double->reveal(), $this->userService_double->reveal());
        $today = new \DateTime('2015-10-07');
        $sut->createBetAction($today, $this->time_to_retry);
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

    /**
     * method createBetAction
     * when calledWithTimeLessThanNow
     * should createBetOrRetryCreateBet
     */
    public function test_createBetAction_calledWithTimeLessThanNow_createBetOrRetryCreateBet()
    {

        $this->prepareCheckValidation();
        $this->playService_double->bet(Argument::any(),Argument::any())->shouldBeCalled();
        $this->userService_double->getUser(Argument::any())->willReturn($this->getUser());
        $this->userService_double->getActiveNotificationsByUserAndType(Argument::any(),Argument::any())->willReturn(new ActionResult(true,$this->getUserNotifications()));
        $sut = new BetTask();
        $sut->initialize($this->lotteryService_double->reveal(), $this->playService_double->reveal(),$this->emailService_double->reveal(), $this->userService_double->reveal());
        $today = new \DateTime('2015-10-07');
        $sut->createBetAction($today,$this->time_to_retry);
    }

    /**
     * method createBetAction
     * when calledPassedTimeLimit
     * should sendEmailToInformAboutProblemsValidation
     */
    public function test_createBetAction_calledPassedTimeLimit_sendEmailToInformAboutProblemsValidation()
    {
        $time_to_retry = 1458686313;
        $euroMillionsDraw = $this->getEuroMillionsDraw('2015-10-09');
        $this->lotteryService_double->getNextDrawByLottery('EuroMillions')->willReturn(new ActionResult(true,$euroMillionsDraw));
        $play_config_list = $this->getPlayConfigList($this->getUser());
        $this->playService_double->getPlaysConfigToBet($euroMillionsDraw->getDrawDate())->willReturn($play_config_list);
        $this->playService_double->bet(Argument::any(),Argument::any())->shouldNotBeCalled();
        $this->userService_double->getUser(Argument::any())->willReturn($this->getUser());
        $this->lotteryService_double->getNextJackpot('EuroMillions')->willReturn(new Money(10000,new Currency('EUR')));
        $this->lotteryService_double->getNextDateDrawByLottery('EuroMillions')->willReturn(new \DateTime());
        $this->emailService_double->sendTransactionalEmail($this->getUser(),Argument::type('EuroMillions\web\emailTemplates\IEmailTemplate'))->shouldBeCalled();
        $sut = new BetTask();
        $sut->initialize($this->lotteryService_double->reveal(), $this->playService_double->reveal(),$this->emailService_double->reveal(), $this->userService_double->reveal());
        $today = new \DateTime('2015-10-07');
        $sut->createBetAction($today,$time_to_retry);
    }


    /**
     * method createBetAction
     * when jackpotDrawIsgreatherThanJackpotConfigUser
     * should createBet
     */
    public function test_createBetAction_jackpotDrawIsgreatherThanJackpotConfigUser_createBet()
    {
        $this->markTestIncomplete('Para cuando se refactorice');
        $euroMillionsDraw = $this->getEuroMillionsDraw('2015-11-20');
        $this->lotteryService_double->getNextDrawByLottery('EuroMillions')->willReturn(new ActionResult(true,$euroMillionsDraw));
        $play_config_list = $this->getPlayConfigList($this->getUserTwo());
        $this->playService_double->getPlaysConfigToBet($euroMillionsDraw->getDrawDate())->willReturn($play_config_list);
        $this->playService_double->bet(Argument::any(),Argument::any())->shouldBeCalled();
        $sut = new BetTask();
        $sut->initialize($this->lotteryService_double->reveal(), $this->playService_double->reveal(),$this->emailService_double->reveal(), $this->userService_double->reveal());
        $today = new \DateTime('2015-11-20');
        $sut->createBetAction($today,$this->time_to_retry);
    }

    private function getUser()
    {
        return UserMother::aUserWith50Eur()
            ->build();
    }

    /**
     * @param string $currency
     * @return User
     */
    private function getUserTwo($currency = 'EUR')
    {
        return UserMother::aUserWith50Eur()
            ->build();
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

    private function prepareCheckValidation()
    {
        $euroMillionsDraw = $this->getEuroMillionsDraw('2015-10-09');
        $this->lotteryService_double->getNextDrawByLottery('EuroMillions')->willReturn(new ActionResult(true, $euroMillionsDraw));
        $play_config_list = $this->getPlayConfigList($this->getUser());
        $this->playService_double->getPlaysConfigToBet($euroMillionsDraw->getDrawDate())->willReturn($play_config_list);
    }

    private function getEuroMillionsDraw($lotteryDrawDate)
    {
        $regular_numbers = [1, 2, 3, 4, 5];
        $lucky_numbers = [5, 8];
        $euroMillionsDraw = new EuroMillionsDraw();
        $euroMillionsDraw->setDrawDate((new \DateTime($lotteryDrawDate)));
        $euroMillionsDraw->setJackpot(new Money(1000000, new Currency('EUR')));
        $euroMillionsDraw->createResult($regular_numbers, $lucky_numbers);
        return $euroMillionsDraw;
    }


    private function getPlayConfigList($user)
    {

        $attributes_list = [
            [
                'active'        => 1,
                'startDrawDate' => new \DateTime('2015-10-05'),
                'lastDrawDate'  => new \DateTime('2015-12-03'),
                'draw_days'     => new DrawDays('25'),
                'user'          => $user
            ],
            [
                'active'        => 1,
                'startDrawDate' => new \DateTime('2015-10-05'),
                'lastDrawDate'  => new \DateTime('2015-12-03'),
                'draw_days'     => new DrawDays('5'),
                'user'          => $user
            ],
            [
                'active'        => 1,
                'startDrawDate' => new \DateTime('2015-10-07'),
                'lastDrawDate'  => new \DateTime('2015-12-03'),
                'draw_days'     => new DrawDays('5'),
                'user'          => $user
            ],
            [
                'active'        => 1,
                'startDrawDate' => new \DateTime('2015-10-05'),
                'lastDrawDate'  => new \DateTime('2015-12-03'),
                'draw_days'     => new DrawDays('2'),
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