<?php
namespace EuroMillions\tests\unit;

use EuroMillions\shared\config\Namespaces;
use EuroMillions\web\entities\Notification;
use EuroMillions\web\entities\User;
use EuroMillions\web\entities\UserNotifications;
use EuroMillions\web\tasks\JackpotTask;
use EuroMillions\web\vo\NotificationValue;
use EuroMillions\shared\vo\results\ActionResult;
use Money\Currency;
use Money\Money;
use Prophecy\Argument;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\UserMother;

class JackpotTaskUnitTest extends UnitTestBase
{

    private $euroMillionsDrawRepository_double;

    private $lotteryDrawRepository_double;

    private $lotteriesDataService_double;
    private $lotteryService_double;

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
        $this->lotteriesDataService_double = $this->getServiceDouble('LotteriesDataService');
        $this->lotteryService_double = $this->getServiceDouble('LotteryService');
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
        $this->lotteryService_double->getLastDrawDate($lottery_name,$today)->willReturn(new \DateTime('2015-06-09 20:00:00'));
        $this->lotteriesDataService_double->updateNextDrawJackpot($lottery_name,new \DateTime('2015-06-09 19:59:00'))->shouldBeCalled();
        $sut = new JackpotTask();
        $sut->initialize($this->lotteriesDataService_double->reveal(),$this->lotteryService_double->reveal(),$this->userService_double->reveal(), $this->emailService_double->reveal());
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
        $jackpot_amount = new Money(4000000000, new Currency('EUR'));
        $this->lotteryService_double->getNextDateDrawByLottery($lottery_name)->willReturn(new \DateTime());
        $this->lotteryService_double->getNextJackpot($lottery_name)->willReturn($jackpot_amount);
        $this->userService_double->getActiveNotificationsTypeJackpot()->willReturn(new ActionResult(true,$notifications));
        $this->userService_double->getUser($this->getUser()->getId())->willReturn($this->getUser());
        $this->emailService_double->sendTransactionalEmail(Argument::type($this->getEntitiesToArgument('User')), Argument::type('EuroMillions\web\emailTemplates\IEmailTemplate'))->shouldBeCalledTimes(1);
        $sut = new JackpotTask();
        $sut->initialize($this->lotteriesDataService_double->reveal(),$this->lotteryService_double->reveal(),$this->userService_double->reveal(), $this->emailService_double->reveal());
        $sut->reminderJackpotAction();
    }

    /**
     * @return User
     */
    private function getUser()
    {
        return UserMother::aUserWith50Eur()
            ->build();
    }

    private function getUserNotifications()
    {
        $userNotifications = new UserNotifications();
        $userNotifications->setUser($this->getUser());
        $notification = new Notification();
        $notification->setDescription('Test');
        $userNotifications->setNotification($notification);
        $notificationType = new NotificationValue(1,35000000);
        $userNotifications->setConfigValue($notificationType);
        $userNotifications->setActive(true);
        return $userNotifications;
    }
}