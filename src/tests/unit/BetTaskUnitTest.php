<?php


namespace tests\unit;


use EuroMillions\web\components\NullPasswordHasher;
use EuroMillions\shareconfig\Namespaces;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\exceptions\InvalidBalanceException;
use EuroMillions\web\tasks\BetTask;
use EuroMillions\web\vo\DrawDays;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\EuroMillionsLine;
use EuroMillions\web\vo\Password;
use EuroMillions\web\vo\ActionResult;
use EuroMillions\web\vo\UserId;
use Money\Currency;
use Money\Money;
use Prophecy\Argument;
use tests\base\EuroMillionsResultRelatedTest;
use tests\base\UnitTestBase;

class BetTaskUnitTest extends UnitTestBase
{

    use EuroMillionsResultRelatedTest;

    private $playConfigRepository_double;

    private $euroMillionsDrawRepository_double;

    private $lotteryDrawRepository_double;

    private $lotteryDataService_double;

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
        $this->lotteryDataService_double = $this->getServiceDouble('LotteriesDataService');
        $this->playService_double = $this->getServiceDouble('PlayService');
        $this->emailService_double = $this->getServiceDouble('EmailService');
        $this->userService_double = $this->getServiceDouble('UserService');
        $this->time_to_retry = 1603738800;
        parent::setUp();
    }


    private function getSut()
    {
        $sut = $this->getDomainServiceFactory();
        return $sut;
    }


    private function getEuroMillionsDraw($lotteryDrawDate)
    {
        $regular_numbers = [1, 2, 3, 4, 5];
        $lucky_numbers = [5, 8];
        $euroMillionsDraw = new EuroMillionsDraw();
        $euroMillionsDraw->setDrawDate((new \DateTime($lotteryDrawDate)));
        $euroMillionsDraw->createResult($regular_numbers, $lucky_numbers);
        return $euroMillionsDraw;
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
        $this->lotteryDataService_double->getNextDrawByLottery('EuroMillions')->willReturn(new ActionResult(true,$euroMillionsDraw));
        $play_config_list = $this->getPlayConfigList();
        $this->playService_double->getPlaysConfigToBet($euroMillionsDraw->getDrawDate())->willReturn($play_config_list);
        $this->playService_double->bet(Argument::type('EuroMillions\web\entities\PlayConfig'), $euroMillionsDraw)->shouldBeCalledTimes($callTimes);
        $sut = new BetTask();
        $sut->initialize($this->lotteryDataService_double->reveal(), $this->playService_double->reveal(), $this->emailService_double->reveal(), $this->userService_double->reveal());
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
     * method createBet
     * when called
     * should sendProperArgumentsToEachCallOfPlayServiceBet
     */
    public function test_createBet_called_sendProperArgumentsToEachCallOfPlayServiceBet()
    {
        $euroMillionsDraw = $this->getEuroMillionsDraw('2015-10-09');
        $this->lotteryDataService_double->getNextDrawByLottery('EuroMillions')->willReturn(new ActionResult(true,$euroMillionsDraw));
        $play_config_list = $this->getPlayConfigList();
        $this->playService_double->getPlaysConfigToBet($euroMillionsDraw->getDrawDate())->willReturn($play_config_list);
        $play_config = $this->getPlayConfigList();

        $this->playService_double->bet($play_config->getValues()[0], $euroMillionsDraw)->shouldBeCalled();
        $this->playService_double->bet($play_config->getValues()[1], $euroMillionsDraw)->shouldBeCalled();
        $this->playService_double->bet($play_config->getValues()[2], $euroMillionsDraw)->shouldBeCalled();

        $sut = new BetTask();
        $sut->initialize($this->lotteryDataService_double->reveal(), $this->playService_double->reveal(),$this->emailService_double->reveal(), $this->userService_double->reveal());
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
        $this->lotteryDataService_double->getNextDrawByLottery('EuroMillions')->willReturn(new ActionResult(true,$euroMillionsDraw));
        $play_config_list = $this->getPlayConfigList();
        $this->playService_double->getPlaysConfigToBet($euroMillionsDraw->getDrawDate())->willReturn($play_config_list);
        $this->playService_double->bet(Argument::type('EuroMillions\web\entities\PlayConfig'), $euroMillionsDraw)->shouldBeCalledTimes(1);
        $this->playService_double->bet(Argument::type('EuroMillions\web\entities\PlayConfig'), $euroMillionsDraw)->willThrow(new InvalidBalanceException());
        $this->userService_double->getUser(Argument::any())->willReturn($this->getUser());
        $this->emailService_double->sendTransactionalEmail(Argument::type('EuroMillions\web\entities\User'),'low-balance')->shouldBeCalledTimes(1);
        $sut = new BetTask();
        $sut->initialize($this->lotteryDataService_double->reveal(),
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
        $this->lotteryDataService_double->getNextDrawByLottery('EuroMillions')->willReturn(new ActionResult(true,$euroMillionsDraw));
        $this->playService_double->getPlaysConfigToBet($euroMillionsDraw->getDrawDate())->willReturn(new ActionResult(false));
        $this->playService_double->bet(Argument::any(),Argument::any())->shouldNotBeCalled();
        $sut = new BetTask();
        $sut->initialize($this->lotteryDataService_double->reveal(), $this->playService_double->reveal(),$this->emailService_double->reveal(), $this->userService_double->reveal());
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
        $result_play_config = $this->getPlayConfigList();
        $user = $this->getUser();
        $this->playService_double->getPlaysConfigToBet($today)->willReturn($result_play_config);
        $this->userService_double->getUser(new UserId('9098299B-14AC-4124-8DB0-19571EDABE55'))->willReturn($user);
        $this->emailService_double->sendTransactionalEmail(Argument::type('EuroMillions\web\entities\User'),'long-play-is-ended')->shouldBeCalledTimes(4);
        $sut = new BetTask();
        $sut->initialize($this->lotteryDataService_double->reveal(), $this->playService_double->reveal(),$this->emailService_double->reveal(), $this->userService_double->reveal());
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
        $sut = new BetTask();
        $sut->initialize($this->lotteryDataService_double->reveal(), $this->playService_double->reveal(),$this->emailService_double->reveal(), $this->userService_double->reveal());
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
        $time_to_retry = 1445880600;
        $euroMillionsDraw = $this->getEuroMillionsDraw('2015-10-09');
        $this->lotteryDataService_double->getNextDrawByLottery('EuroMillions')->willReturn(new ActionResult(true,$euroMillionsDraw));
        $play_config_list = $this->getPlayConfigList();
        $this->playService_double->getPlaysConfigToBet($euroMillionsDraw->getDrawDate())->willReturn($play_config_list);
        $this->playService_double->bet(Argument::any(),Argument::any())->shouldNotBeCalled();
        $this->userService_double->getUser(Argument::any())->willReturn($this->getUser());
        $this->emailService_double->sendTransactionalEmail($this->getUser(),Argument::type('string'))->shouldBeCalled();
        $sut = new BetTask();
        $sut->initialize($this->lotteryDataService_double->reveal(), $this->playService_double->reveal(),$this->emailService_double->reveal(), $this->userService_double->reveal());
        $today = new \DateTime('2015-10-07');
        $sut->createBetAction($today,$time_to_retry);
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
                'id' => new UserId('9098299B-14AC-4124-8DB0-19571EDABE55'),
                'name'     => 'test',
                'surname'  => 'test01',
                'email'    => new Email('raul.mesa@panamedia.net'),
                'password' => new Password('passworD01', new NullPasswordHasher()),
                'validated' => false,
                'balance' => new Money(5000,new Currency($currency)),
                'validation_token' => '33e4e6a08f82abb38566fc3bb8e8ef0d'
            ]
        );
        return $user;
    }

    /**
     * @param string $currency
     * @return User
     */
    private function getUserTwo($currency = 'EUR')
    {
        $user = new User();
        $user->initialize(
            [
                'id' => new UserId('9098299B-14AC-4124-8DB0-19571EDABE56'),
                'name'     => 'test',
                'surname'  => 'test01',
                'email'    => new Email('raul.mesa@panamedia.net'),
                'password' => new Password('passworD01', new NullPasswordHasher()),
                'validated' => false,
                'balance' => new Money(5000,new Currency($currency)),
                'validation_token' => '33e4e6a08f82abb38566fc3bb8e8ef0d'
            ]
        );
        return $user;
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
        $this->lotteryDataService_double->getNextDrawByLottery('EuroMillions')->willReturn(new ActionResult(true, $euroMillionsDraw));
        $play_config_list = $this->getPlayConfigList();
        $this->playService_double->getPlaysConfigToBet($euroMillionsDraw->getDrawDate())->willReturn($play_config_list);
    }
}