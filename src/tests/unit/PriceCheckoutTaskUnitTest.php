<?php


namespace tests\unit;


use EuroMillions\shared\vo\Wallet;
use EuroMillions\web\components\NullPasswordHasher;
use EuroMillions\shared\config\Namespaces;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\tasks\PriceCheckoutTask;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;
use EuroMillions\web\vo\EuroMillionsLine;
use EuroMillions\web\vo\Password;
use EuroMillions\web\vo\ActionResult;
use EuroMillions\web\vo\UserId;
use Money\Currency;
use Money\Money;
use Prophecy\Argument;
use tests\base\EuroMillionsResultRelatedTest;
use tests\base\UnitTestBase;

class PriceCheckoutTaskUnitTest extends UnitTestBase
{
    use EuroMillionsResultRelatedTest;

    private $playConfigRepository_double;

    private $euroMillionsDrawRepository_double;

    private $lotteryDrawRepository_double;

    private $lotteryDataService_double;

    private $betRepository_double;

    private $userRepository_double;

    private $playService_double;

    private $priceCheckoutService_double;

    private $emailService_double;

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
        $this->priceCheckoutService_double = $this->getServiceDouble('PriceCheckoutService');
        $this->betRepository_double = $this->getRepositoryDouble('BetRepository');
        $this->emailService_double = $this->getServiceDouble('EmailService');
        parent::setUp();
    }


    /**
     * method checkout
     * when called
     * should callchargeAmountAwardedToUserFromPriceCheckoutServiceOnceEachPlayConfigAwarded
     */
    public function test_checkout_called_callchargeAmountAwardedToUserFromPriceCheckoutServiceOnceEachPlayConfigAwarded()
    {
        $today = new \DateTime('2015-10-06');
        $lottery_name = 'EuroMillions';
        $user = $this->getUser();
        $result_awarded = $this->getPlayConfigsAwarded();
        $this->priceCheckoutService_double->playConfigsWithBetsAwarded($today)->willReturn(new ActionResult(true,$result_awarded));
        $this->lotteryDataService_double->getBreakDownDrawByDate($lottery_name,$today)->willReturn(new ActionResult(true,new EuroMillionsDrawBreakDown($this->getBreakDownDataDraw())));
        $this->priceCheckoutService_double->reChargeAmountAwardedToUser($user,Argument::any())->willReturn(new ActionResult(true))->shouldBeCalledTimes(2);
        $sut = new PriceCheckoutTask();
        $sut->initialize($this->priceCheckoutService_double->reveal(), $this->lotteryDataService_double->reveal());
        $sut->checkoutAction($today);
    }

    /**
     * method checkout
     * when calledAndAmountAwardedIsMoreThan1500
     * should sendEmailToUserReminder
     */
    public function test_checkout_calledAndAmountAwardedIsMoreThan1500_sendEmailToUserReminder()
    {
        $today = new \DateTime('2015-10-06');
        $lottery_name = 'EuroMillions';
        $user = $this->getUser();
        $result_awarded = $this->getPlayConfigsAwarded();
        $this->priceCheckoutService_double->playConfigsWithBetsAwarded($today)->willReturn(new ActionResult(true,$result_awarded));
        $this->lotteryDataService_double->getBreakDownDrawByDate($lottery_name,$today)->willReturn(new ActionResult(true,new EuroMillionsDrawBreakDown($this->getBreakDownDataDraw())));
        $this->priceCheckoutService_double->reChargeAmountAwardedToUser($user,Argument::any())->willReturn(new ActionResult(true))->shouldBeCalledTimes(2);
        $this->emailService_double->sendTransactionalEmail($user,Argument::type('EuroMillions\web\emailTemplates\IEmailTemplate'))->shouldBeCalled();
        $sut = new PriceCheckoutTask();
        $sut->initialize($this->priceCheckoutService_double->reveal(), $this->lotteryDataService_double->reveal(), $this->emailService_double->reveal());
        $sut->checkoutAction($today);
    }


    private function getSut(){
        $sut = $this->getDomainServiceFactory();
        return $sut;
    }


    private function getPlayConfigsAwarded()
    {
        $user = $this->getUser();
        $regular_numbers = [1, 2, 3, 4, 5];
        $lucky_numbers = [5, 8];
        $euroMillionsDraw = new EuroMillionsDraw();
        $euroMillionsLine = new EuroMillionsLine($this->getRegularNumbers($regular_numbers),
            $this->getLuckyNumbers($lucky_numbers));
        $euroMillionsDraw->createResult($regular_numbers, $lucky_numbers);
        $playConfig = new PlayConfig();
        $playConfig->initialize([
                'user' => $user,
                'line' => $euroMillionsLine

            ]
        );
        $regular_numbers = [11, 20, 22, 34, 35];
        $lucky_numbers = [1, 8];
        $euroMillionsDraw = new EuroMillionsDraw();
        $euroMillionsLine = new EuroMillionsLine($this->getRegularNumbers($regular_numbers),
            $this->getLuckyNumbers($lucky_numbers));
        $euroMillionsDraw->createResult($regular_numbers, $lucky_numbers);

        $playConfig2 = new PlayConfig();
        $playConfig2->initialize([
                'user' => $user,
                'line' => $euroMillionsLine
            ]
        );
        return [
            [
                $playConfig,5,1,
            ],
            [
                $playConfig2,4,2
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
                'id' => new UserId('9098299B-14AC-4124-8DB0-19571EDABE55'),
                'name'     => 'test',
                'surname'  => 'test01',
                'email'    => new Email('raul.mesa@panamedia.net'),
                'password' => new Password('passworD01', new NullPasswordHasher()),
                'validated' => false,
                'wallet' => new Wallet(new Money(5000,new Currency('EUR'))),
                'validation_token' => '33e4e6a08f82abb38566fc3bb8e8ef0d'
            ]
        );
        return $user;
    }

    protected function getBreakDownDataDraw()
    {
        return [
            [
                'category_one' => ['5 + 2', '000', '0'],
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