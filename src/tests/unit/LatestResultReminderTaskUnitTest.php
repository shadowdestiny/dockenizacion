<?php


namespace EuroMillions\tests\unit;


use EuroMillions\shared\config\Namespaces;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\web\entities\Bet;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\tasks\LatestresultTask;
use EuroMillions\web\vo\dto\EuroMillionsDrawBreakDownDTO;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;
use EuroMillions\web\vo\EuroMillionsLine;
use EuroMillions\web\vo\EuroMillionsLuckyNumber;
use EuroMillions\web\vo\EuroMillionsRegularNumber;
use Money\Currency;
use Money\Money;
use Prophecy\Argument;

class LatestResultReminderTaskUnitTest extends UnitTestBase
{

    private $euroMillionsDrawRepository_double;
    private $lotteryDrawRepository_double;
    private $lotteryService_double;
    private $emailService_double;
    private $userService_double;
    private $betService_double;
    private $userNotificationService_double;



    protected function getEntityManagerStubExtraMappings()
    {
        return [
            Namespaces::ENTITIES_NS . 'EuroMillionsDraw' => $this->euroMillionsDrawRepository_double,
            Namespaces::ENTITIES_NS . 'Lottery' => $this->lotteryDrawRepository_double,
        ];
    }

    public function setUp()
    {
        $this->lotteryService_double = $this->getServiceDouble('LotteryService');
        $this->userService_double = $this->getServiceDouble('UserService');
        $this->emailService_double = $this->getServiceDouble('EmailService');
        $this->betService_double = $this->getServiceDouble('BetService');
        $this->userNotificationService_double = $this->getServiceDouble('UserNotificationsService');
        parent::setUp();
    }


    /**
     * method resultReminderAction
     * when called
     * should callServiceWithDateOfPreviousDraw
     */
    public function test_resultReminderAction_called_callServiceWithDateOfPreviousDraw()
    {
        $user = UserMother::aUserWith50Eur()->build();
        list($playConfig,$euroMillionsDraw) = $this->getPlayConfigAndEuroMillionsDraw($user);
        $bet = new Bet($playConfig,$euroMillionsDraw);
        $emailDataStrategy_double = $this->getInterfaceWebDouble('IEmailTemplateDataStrategy');
        $date = new \DateTime();
        $this->lotteryService_double->getLastDrawDate('EuroMillions')->willReturn($date);
        $this->lotteryService_double->getLastDrawWithBreakDownByDate('EuroMillions',$date)->willReturn(new ActionResult(true,$euroMillionsDraw));
        $this->betService_double->getBetsPlayedLastDraw($euroMillionsDraw->getDrawDate())->willReturn([$bet]);
        $this->lotteryService_double->sendResultLotteryToUsersWithBets(Argument::any(),Argument::any())->shouldBeCalled();
        $this->userService_double->getAllUsers()->willReturn([$user]);
        $this->lotteryService_double->sendResultLotteryToUsers(Argument::any(),Argument::any())->shouldBeCalled();
        $sut = new LatestresultTask();
        $sut->initialize($this->lotteryService_double->reveal(),$this->userService_double->reveal(), $this->betService_double->reveal());
        $sut->resultsReminderWhenPlayedAction(null,$emailDataStrategy_double->reveal());
    }

    /**
     * method resultReminderAction
     * when called
     * should callServiceShouldNotBeCalled
     */
    public function test_resultReminderAction_called_callServiceShouldNotBeCalled()
    {
        $user = UserMother::aUserWith50Eur()->build();
        list($playConfig,$euroMillionsDraw) = $this->getPlayConfigAndEuroMillionsDraw($user);
        $bet = new Bet($playConfig,$euroMillionsDraw);
        $emailDataStrategy_double = $this->getInterfaceWebDouble('IEmailTemplateDataStrategy');
        $date = new \DateTime();
        $this->lotteryService_double->getLastDrawDate('EuroMillions')->willReturn($date);
        $this->lotteryService_double->getLastDrawWithBreakDownByDate('EuroMillions',$date)->willReturn(new ActionResult(true,$euroMillionsDraw));
        $this->betService_double->getBetsPlayedLastDraw($euroMillionsDraw->getDrawDate())->willReturn(null);
        $this->lotteryService_double->sendResultLotteryToUsersWithBets(Argument::any(),Argument::any())->shouldNotBeCalled();
        $this->userService_double->getAllUsers()->willReturn([$user]);
        $this->lotteryService_double->sendResultLotteryToUsers(Argument::any(),Argument::any())->shouldBeCalled();
        $sut = new LatestresultTask();
        $sut->initialize($this->lotteryService_double->reveal(),$this->userService_double->reveal(), $this->betService_double->reveal());
        $sut->resultsReminderWhenPlayedAction(null,$emailDataStrategy_double->reveal());

    }


    private function getPlayConfigAndEuroMillionsDraw($user)
    {
        $regular_numbers = [1, 2, 3, 4, 5];
        $lucky_numbers = [5, 8];
        $euroMillionsDraw = new EuroMillionsDraw();
        $euroMillionsLine = new EuroMillionsLine($this->getRegularNumbers($regular_numbers),
            $this->getLuckyNumbers($lucky_numbers));
        $euroMillionsDraw->createResult($regular_numbers, $lucky_numbers);
        $euroMillionsDraw->setBreakDown(new EuroMillionsDrawBreakDown($this->getBreakDownDataDraw()));
        $euroMillionsDraw->setDrawDate(new \DateTime());
        $lottery = new Lottery();
        $lottery->initialize([
            'id'        => 1,
            'name'      => 'EuroMillions',
            'active'    => 1,
            'frequency' => 'freq',
            'draw_time' => 'draw',
            'single_bet_price' => new Money(23500, new Currency('EUR')),
        ]);
        $euroMillionsDraw->setLottery($lottery);
        $playConfig = new PlayConfig();
        $playConfig->initialize([
                'user' => $user,
                'line' => $euroMillionsLine
            ]
        );
        return [$playConfig,$euroMillionsDraw];
    }

    protected function getRegularNumbers(array $numbers)
    {
        $result = [];
        foreach ($numbers as $number) {
            $result[] = new EuroMillionsRegularNumber($number);
        }
        return $result;
    }
    protected function getLuckyNumbers(array $numbers)
    {
        $result = [];
        foreach ($numbers as $number) {
            $result[] = new EuroMillionsLuckyNumber($number);
        }
        return $result;
    }

    protected function getBreakDownDataDraw()
    {
        return [
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
        ];
    }

}