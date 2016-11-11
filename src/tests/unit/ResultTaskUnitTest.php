<?php
namespace EuroMillions\tests\unit;

use EuroMillions\shared\vo\Wallet;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\web\components\NullPasswordHasher;
use EuroMillions\shared\config\Namespaces;
use EuroMillions\web\entities\User;
use EuroMillions\web\tasks\RaffleTask;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\Password;
use Money\Currency;
use Money\Money;
use Prophecy\Argument;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\entities\PlayConfig;
//
use EuroMillions\web\tasks\ResultTask;

class ResultTaskUnitTest extends UnitTestBase
{
    private $emailService_double;
    private $lotteryDataService_double;
    private $playService_double;
    private $userService_double;
    private $currencyService_double;

    public function setUp()
    {
        $this->lotteryDataService_double = $this->getServiceDouble('LotteriesDataService');
        $this->playService_double = $this->getServiceDouble('PlayService');
        $this->emailService_double = $this->getServiceDouble('EmailService');
        $this->userService_double = $this->getServiceDouble('UserService');
        $this->currencyService_double = $this->getServiceDouble('CurrencyService');
        parent::setUp();
    }

    public function test_SendEmailLogAction()
    {
        $this->emailService_double->sendLog(Argument::any(), Argument::any(), Argument::any(), Argument::any())->shouldBeCalled();
        $this->lotteryDataService_double->updateLastBreakDown('EuroMillions')->WillThrow(new \Exception);
        $sut = new ResultTask();
        $sut->initialize($this->lotteryDataService_double->reveal(), $this->playService_double->reveal(), $this->emailService_double->reveal(), $this->userService_double->reveal(), $this->currencyService_double->reveal());
        $sut->updateAction(new \DateTime());
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
}