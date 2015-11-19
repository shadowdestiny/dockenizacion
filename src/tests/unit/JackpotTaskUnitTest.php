<?php
namespace tests\unit;

use EuroMillions\web\components\NullPasswordHasher;
use EuroMillions\shareconfig\Namespaces;
use EuroMillions\web\entities\Notification;
use EuroMillions\web\entities\User;
use EuroMillions\web\entities\UserNotifications;
use EuroMillions\web\tasks\JackpotTask;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\NotificationType;
use EuroMillions\web\vo\Password;
use EuroMillions\web\vo\ActionResult;
use EuroMillions\web\vo\UserId;
use Money\Currency;
use Money\Money;
use Prophecy\Argument;
use tests\base\UnitTestBase;

class JackpotTaskUnitTest extends UnitTestBase
{

    private $euroMillionsDrawRepository_double;

    private $lotteryDrawRepository_double;

    private $lotteryDataService_double;

    private $emailService_double;

    private $userService_double;



    protected function getEntityManagerStubExtraMappings()
    {
        return [
            Namespaces::ENTITIES_NS . 'EuroMillionsDraw' => $this->euroMillionsDrawRepository_double,
            Namespaces::ENTITIES_NS . 'Lottery' => $this->lotteryDrawRepository_double,
        ];
    }

    public function setUp()
    {
        $this->lotteryDataService_double = $this->getServiceDouble('LotteriesDataService');
        $this->userService_double = $this->getServiceDouble('UserService');
        $this->emailService_double = $this->getServiceDouble('EmailService');
        parent::setUp();
    }

    /**
     * method updatePreviousAction
     * when called
     * should callServiceWithDateOfPreviousDraw
     */
    public function test_updatePreviousAction_called_callServiceWithDateOfPreviousDraw()
    {
        $today = new \DateTime('2015-06-12 10:37:08');
        $lottery_name = 'EuroMillions';
        $this->lotteryDataService_double->getLastDrawDate($lottery_name,$today)->willReturn(new \DateTime('2015-06-09 20:00:00'));
        $this->lotteryDataService_double->updateNextDrawJackpot($lottery_name,new \DateTime('2015-06-09 19:59:00'))->shouldBeCalled();
        $sut = new JackpotTask();
        $sut->initialize($this->lotteryDataService_double->reveal(),$this->userService_double->reveal(), $this->emailService_double->reveal());
        $sut->updatePreviousAction($today);
    }

    /**
     * method reminderJackpotAction
     * when called
     * should sendReminderAllUsers
     */
    public function test_reminderJackpotAction_called_sendReminderAllUsers()
    {
        $lottery_name = 'EuroMillions';
        $notifications = [$this->getUserNotifications()];
        $jackpot_amount = new Money(40000000, new Currency('EUR'));
        $this->lotteryDataService_double->getNextDateDrawByLottery($lottery_name)->willReturn(new \DateTime());
        $this->lotteryDataService_double->getNextJackpot($lottery_name)->willReturn($jackpot_amount);
        $this->userService_double->getActiveNotificationsTypeJackpot()->willReturn(new ActionResult(true,$notifications));
        $this->userService_double->getUser($this->getUser()->getId())->willReturn($this->getUser());
        $this->emailService_double->sendTransactionalEmail(Argument::type($this->getEntitiesToArgument('User')), 'jackpot-rollover',Argument::any())->shouldBeCalledTimes(1);
        $sut = new JackpotTask();
        $sut->initialize($this->lotteryDataService_double->reveal(),$this->userService_double->reveal(), $this->emailService_double->reveal());
        $sut->reminderJackpotAction();
    }


    private function getSut()
    {
        $sut = $this->getDomainServiceFactory();
        return $sut;
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

    private function getUserNotifications()
    {
        $userNotifications = new UserNotifications();
        $userNotifications->setUser($this->getUser());
        $notification = new Notification();
        $notification->setDescription('Test');
        $userNotifications->setNotification($notification);
        $notificationType = new NotificationType(1,3500000);
        $userNotifications->setConfigValue($notificationType);
        $userNotifications->setActive(true);
        return $userNotifications;
    }
}