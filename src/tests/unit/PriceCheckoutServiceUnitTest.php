<?php


namespace EuroMillions\tests\unit;


use EuroMillions\shared\vo\Wallet;
use EuroMillions\web\components\NullPasswordHasher;
use EuroMillions\shared\config\Namespaces;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\services\PriceCheckoutService;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\EuroMillionsLine;
use EuroMillions\web\vo\Password;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\vo\UserId;
use Money\Currency;
use Money\Money;
use Prophecy\Argument;
use EuroMillions\tests\base\EuroMillionsResultRelatedTest;
use EuroMillions\tests\base\UnitTestBase;

class PriceCheckoutServiceUnitTest extends UnitTestBase
{

    use EuroMillionsResultRelatedTest;

    private $playConfigRepository_double;

    private $euroMillionsDrawRepository_double;

    private $lotteryDrawRepository_double;

    private $lotteryDataService_double;

    private $betRepository_double;

    private $playStorageStrategy_double;

    private $userRepository_double;

    private $authService_double;

    private $emailService_double;

    private $currencyConversionService_double;

    private $userService_double;


    protected function getEntityManagerStubExtraMappings()
    {
        return [
            Namespaces::ENTITIES_NS . 'User' => $this->userRepository_double,
            Namespaces::ENTITIES_NS . 'PlayConfig' => $this->playConfigRepository_double,
            Namespaces::ENTITIES_NS . 'EuroMillionsDraw' => $this->euroMillionsDrawRepository_double,
            Namespaces::ENTITIES_NS . 'Lottery' => $this->lotteryDrawRepository_double,
            Namespaces::ENTITIES_NS . 'Bet' => $this->betRepository_double,
        ];
    }

    public function setUp()
    {
        $this->playConfigRepository_double = $this->getRepositoryDouble('PlayConfigRepository');
        $this->lotteryDataService_double = $this->getServiceDouble('LotteriesDataService');
        $this->betRepository_double = $this->getRepositoryDouble('BetRepository');
        $this->lotteryDrawRepository_double = $this->getRepositoryDouble('EuroMillions\entities\Lottery');
        $this->playStorageStrategy_double = $this->getInterfaceDouble('IPlayStorageStrategy');
        $this->userRepository_double = $this->getRepositoryDouble('UserRepository');
        $this->authService_double = $this->getServiceDouble('AuthService');
        $this->emailService_double = $this->getServiceDouble('EmailService');
        $this->currencyConversionService_double = $this->getServiceDouble('CurrencyConversionService');
        $this->userService_double = $this->getServiceDouble('UserService');
        parent::setUp();
    }


    /**
     * method playConfigsWithBetsAwarded
     * when called
     * should returnServiceActionResultTrueWithArrayResult
     */
    public function test_playConfigsWithBetsAwarded_called_returnServiceActionResultTrueWithArrayResult()
    {
        $expected = 1;
        $date = new \DateTime('2015-10-06 00:00:00');
        $sut = $this->getSut();
        $play_configs_result = $this->getPlayConfigsAwarded();
        $this->betRepository_double->getCheckResult($date->format('Y-m-d'))->willReturn($play_configs_result);
        $actual = count($sut->playConfigsWithBetsAwarded($date)->getValues());
        $this->assertGreaterThanOrEqual($expected,$actual);
    }

    /**
     * method playConfigsWithBetsAwarded
     * when calledAndReturnEmptyResult
     * should returnServiceActionResultFalse
     */
    public function test_playConfigsWithBetsAwarded_calledAndReturnEmptyResult_returnServiceActionResultFalse()
    {
        $expected = new ActionResult(false);
        $date = new \DateTime('2015-10-06 00:00:00');
        $sut = $this->getSut();
        $this->betRepository_double->getCheckResult($date->format('Y-m-d'))->willReturn(null);
        $actual = $sut->playConfigsWithBetsAwarded($date);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method reChargeAmountAwardedToUser
     * when called
     * should chargeAmountInHisAccountAndReturnServiceActionResultTrue
     */
    public function test_reChargeAmountAwardedToUser_called_chargeAmountInHisAccountAndReturnServiceActionResultTrue()
    {
        $expected = new ActionResult(true);
        $amount_awarded = new Money(5000, new Currency('EUR'));
        $user = $this->getUser();
        $this->userRepository_double->add($user);
        $this->emailService_double->sendTransactionalEmail(Argument::type('EuroMillions\web\entities\User'), Argument::type('EuroMillions\web\emailTemplates\IEmailTemplate'))->shouldBeCalled();
        $this->iDontCareAboutFlush();
        $sut = $this->getSut();
        $actual = $sut->reChargeAmountAwardedToUser($user,$amount_awarded);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method reChargeAmountAwardedToUser
     * when throwException
     * should returnServiceActionResultFalse
     */
    public function test_chargeAmountAwardedToUser_throwException_returnServiceActionResultFalse()
    {
        $expected = new ActionResult(false);
        $user = $this->getUser();
        $amount_awarded = new Money(5000, new Currency('EUR'));
        $this->userRepository_double->add($user);
        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush($user)->willThrow(new \Exception('Error'));
        $sut = $this->getSut();
        $actual = $sut->reChargeAmountAwardedToUser($user,$amount_awarded);
        $this->assertEquals($expected,$actual);
    }



    private function getSut(){
        return new PriceCheckoutService(
            $this->getEntityManagerRevealed(),
            $this->lotteryDataService_double->reveal(),
            $this->currencyConversionService_double->reveal(),
            $this->userService_double->reveal(),
            $this->emailService_double->reveal()
        );
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
                $playConfig2,4,2
            ],
        ];
    }

    /**
     * @param string $currency
     * @return User
     */
    private function getUser()
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
}