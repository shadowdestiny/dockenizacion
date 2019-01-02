<?php

namespace EuroMillions\tests\unit;

use EuroMillions\shared\vo\Wallet;
use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\web\components\NullPasswordHasher;
use EuroMillions\shared\config\Namespaces;
use EuroMillions\web\entities\Bet;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\services\PrizeCheckoutService;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\EuroMillionsLine;
use EuroMillions\web\vo\Password;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\vo\Raffle;
use Money\Currency;
use Money\Money;
use Prophecy\Argument;
use EuroMillions\tests\base\EuroMillionsResultRelatedTest;
use EuroMillions\tests\base\UnitTestBase;

class PrizeCheckoutServiceUnitTest extends UnitTestBase
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

    private $transactionService;


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
        $this->transactionService = $this->getServiceDouble('TransactionService');

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
        $this->markTestSkipped('Mandrill');
        $expected = new ActionResult(true);
        $amount_awarded = new Money(5000, new Currency('EUR'));
        $user = $this->getUser();
        $this->userRepository_double->add($user);
        $this->emailService_double->sendTransactionalEmail(Argument::type('EuroMillions\web\entities\User'), Argument::type('EuroMillions\web\emailTemplates\IEmailTemplate'))->shouldBeCalled();
        $this->iDontCareAboutFlush();
        $sut = $this->getSut();
        $actual = $sut->awardUser($user,$amount_awarded,$this->getScalarValues());
        $this->assertEquals($expected,$actual);
    }

    /**
     * method awardUser
     * when calledWithAmountLess2500
     * should increaseUserBalance
     */
    public function test_awardUser_calledWithAmountLess2500_increaseUserBalance()
    {
        $this->markTestSkipped('This test don\'t works anymore :( | Fix it? ');

        $bet = $this->prepareAwardUserData();
        $user = UserMother::aUserWith50Eur()->build();
        $amount = new Money(230000, new Currency('EUR'));
        list($playConfig,$euroMillionsDraw) = $this->getPlayConfigAndEuroMillionsDraw();
        $this->userRepository_double->find($user->getId())->willReturn($user);
        $this->transactionService->storeTransaction(Argument::any(),Argument::any())->shouldBeCalled();
        $this->emailService_double->sendTransactionalEmail(Argument::any(),Argument::any())->shouldBeCalled();
        $this->userRepository_double->add($user)->shouldBeCalled();
        $this->playConfigRepository_double->find(1)->willReturn($playConfig);
        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush($user)->shouldBeCalled();
        //$this->iDontCareAboutFlush();
        $sut = $this->getSut();
        $expected = new Money(7300,new Currency('EUR'));
        $actual = $sut->awardUser($bet, $amount, $this->getScalarValues());
        $this->assertEquals($expected,$actual->getValues()->getBalance());
    }

    /**
     * method awardUser
     * when calledWithAmountGreaterThan2500
     * should userBalanceNoIncrease
     */
    public function test_awardUser_calledWithAmountGreaterThan2500_userBalanceNoIncrease()
    {
        $bet = $this->prepareAwardUserData();
        $user = $this->getUser();
        $amount = new Money(40000000, new Currency('EUR'));
        $this->userRepository_double->find($user->getId())->willReturn($user);
        list($playConfig,$euroMillionsDraw) = $this->getPlayConfigAndEuroMillionsDraw();
        $this->transactionService->storeTransaction(Argument::any(),Argument::any())->shouldBeCalled();
        $this->emailService_double->sendTransactionalEmail(Argument::any(),Argument::any())->shouldBeCalled();
        $this->userRepository_double->add($user)->shouldBeCalled();
        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush($user)->shouldBeCalled();
        $this->playConfigRepository_double->find(1)->willReturn($playConfig);
        $sut = $this->getSut();
        $expected = new Money(5000,new Currency('EUR'));
        $actual = $sut->awardUser($bet, $amount, $this->getScalarValues());
        $this->assertEquals($expected,$actual->getValues()->getBalance());
    }

    /**
     * method reChargeAmountAwardedToUser
     * when throwException
     * should returnServiceActionResultFalse
     */
    public function test_chargeAmountAwardedToUser_throwException_returnServiceActionResultFalse()
    {
        $this->markTestSkipped('This test don\'t works anymore :( | Fix it? ');

        $expected = new ActionResult(false);
        $user = $this->getUser();
        list($playConfig,$euroMillionsDraw) = $this->getPlayConfigAndEuroMillionsDraw();
        $bet = new Bet($playConfig,$euroMillionsDraw);
        $amount_awarded = new Money(5000, new Currency('EUR'));
        $this->userRepository_double->add($user);
        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush($user)->willThrow(new \Exception('Error'));
        $sut = $this->getSut();
        $actual = $sut->awardUser($bet, $amount_awarded, $this->getScalarValues());
        $this->assertEquals($expected,$actual);
    }

    /**
     * method award
     * when calledWithAmountLess2500
     * should increaseUserBalance
     */
    public function test_award_calledWithAmountLess2500_increaseUserBalance()
    {
        $this->markTestSkipped('This test don\'t works anymore :( | Fix it? ');

        $bet = $this->prepareAwardUserData();
        $user = UserMother::aUserWith50Eur()->build();

        $originalUser = $user;

        $amount = new Money(230000, new Currency('EUR'));
        list($playConfig,$euroMillionsDraw) = $this->getPlayConfigAndEuroMillionsDraw();

        $this->userRepository_double->find($user->getId())->willReturn($user);
        $this->transactionService->storeTransaction(Argument::any(),Argument::any())->shouldBeCalled();
        $this->emailService_double->sendTransactionalEmail(Argument::any(),Argument::any())->shouldBeCalled();
        $this->userRepository_double->add($user)->shouldBeCalled();
        $this->playConfigRepository_double->find(1)->willReturn($playConfig);
        $this->betRepository_double->findOneBy(array('id' => $bet->getId()))->willReturn($bet);

        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush($user)->shouldBeCalled();
        //$this->iDontCareAboutFlush();
        $sut = $this->getSut();
        $expected = new Money(7300,new Currency('EUR'));
        $actual = $sut->award($bet->getId(), $amount, $this->getScalarValues());

        $this->assertEquals($originalUser, $user);
        $this->assertEquals($expected,$actual->getValues()->getBalance());
    }

    /**
     * method award
     * when calledWithAmountGreaterThan2500
     * should userBalanceNoIncrease
     */
    public function test_award_calledWithAmountGreaterThan2500_userBalanceNoIncrease()
    {
        $bet = $this->prepareAwardUserData();
        $user = $this->getUser();
        $amount = new Money(40000000, new Currency('EUR'));
        $this->userRepository_double->find($user->getId())->willReturn($user);
        list($playConfig,$euroMillionsDraw) = $this->getPlayConfigAndEuroMillionsDraw();
        $this->transactionService->storeTransaction(Argument::any(),Argument::any())->shouldBeCalled();
        $this->emailService_double->sendTransactionalEmail(Argument::any(),Argument::any())->shouldBeCalled();
        $this->userRepository_double->add($user)->shouldBeCalled();
        $this->betRepository_double->findOneBy(array('id' => $bet->getId()))->willReturn($bet);
        $this->playConfigRepository_double->find(1)->willReturn($playConfig);

        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush($user)->shouldBeCalled();

        $sut = $this->getSut();
        $expected = new Money(5000,new Currency('EUR'));
        $actual = $sut->award($bet->getId(), $amount, $this->getScalarValues());
        $this->assertEquals($expected,$actual->getValues()->getBalance());
    }

    /**
     * method reChargeAmountAwarded
     * when throwException
     * should returnServiceActionResultFalse
     */
    public function test_chargeAmountAwarded_throwException_returnServiceActionResultFalse()
    {
        $this->markTestSkipped('This test don\'t works anymore :( | Fix it? ');

        $expected = new ActionResult(false);
        $user = $this->getUser();
        list($playConfig,$euroMillionsDraw) = $this->getPlayConfigAndEuroMillionsDraw();
        $bet = new Bet($playConfig,$euroMillionsDraw);
        $amount_awarded = new Money(5000, new Currency('EUR'));
        $this->userRepository_double->add($user);
        $this->betRepository_double->findOneBy(array('id' => $bet->getId()))->willReturn($bet);

        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush($user)->willThrow(new \Exception('Error'));
        $sut = $this->getSut();
        $actual = $sut->award($bet->getId(), $amount_awarded, $this->getScalarValues());
        $this->assertEquals($expected,$actual);
    }

    /**
     * method matchNumbersUser
     * when called
     * should updateBetEntityWithMatchNumbers
     */
    public function test_matchNumbersUser_called_updateBetEntityWithMatchNumbers()
    {
        $sut = $this->getSut();
        $date = new \DateTime();
        list($playConfig,$euroMillionsDraw) = $this->getPlayConfigAndEuroMillionsDraw();
        $bet = new Bet($playConfig,$euroMillionsDraw);
        $result = ['numbers' => '11,20,22,29,0',
                   'stars' => '1,0'
        ];
        $this->betRepository_double->getMatchNumbers($date,$this->getScalarValues()['userId'])->willReturn($result);
        $this->betRepository_double->findOneBy(['id'=>$bet->getId()])->willReturn($bet);
        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->detach($bet)->shouldBeCalled();
        $this->betRepository_double->add($bet)->shouldBeCalled();
        $this->iDontCareAboutFlush();
        $sut->matchNumbersUser($bet,$this->getScalarValues(), $date, new Money((int) 100 ,new Currency('EUR')));
    }

    /**
     * method sendEmailWinnerRaffle
     * when calledWithADateLastDraw
     * should returnProperResult
     */
    public function test_sendEmailWinnerRaffle_returnProperResult()
    {
        $sut = $this->getSut();
        $betsRaffle = [
            [
                'bet' => "1",
                'raffle' => "BNN41949",
                'playconfig' => "1"
            ], [
                'bet' => "2",
                'raffle' => "BNN41940",
                'playconfig' => "2"
            ]];
        $raffle = new Raffle($betsRaffle[0]['raffle']);
        list($playConfig, $euroMillionsDraw) = $this->getPlayConfigAndEuroMillionsDraw();
        $this->playConfigRepository_double->find(1)->willReturn($playConfig);
        $this->emailService_double->sendLog(Argument::any(), Argument::any(), Argument::any(), Argument::any())->shouldBeCalledTimes(1);
        $sut->sendEmailWinnerRaffle($betsRaffle, $raffle);
    }

    private function getSut(){
        return new PrizeCheckoutService(
            $this->getEntityManagerRevealed(),
            $this->currencyConversionService_double->reveal(),
            $this->userService_double->reveal(),
            $this->emailService_double->reveal(),
            $this->transactionService->reveal()
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

    private function getPlayConfigAndEuroMillionsDraw()
    {
        $user = $this->getUser();
        $regular_numbers = [1, 2, 3, 4, 5];
        $lucky_numbers = [5, 8];
        $euroMillionsDraw = new EuroMillionsDraw();
        $euroMillionsLine = new EuroMillionsLine($this->getRegularNumbers($regular_numbers),
            $this->getLuckyNumbers($lucky_numbers));
        $euroMillionsDraw->createResult($regular_numbers, $lucky_numbers);
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
                'line' => $euroMillionsLine,
                'lottery' => $lottery
            ]
        );
        return [$playConfig,$euroMillionsDraw];
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
                'id' => '9098299B-14AC-4124-8DB0-19571EDABE55',
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

    /**
     * @return Bet
     */
    private function prepareAwardUserData()
    {
        $user = $this->getUser();
        $user->setWallet(Wallet::create(2000, 3000));
        list($playConfig, $euroMillionsDraw) = $this->getPlayConfigAndEuroMillionsDraw();
        $bet = new Bet($playConfig, $euroMillionsDraw);
        return $bet;
    }

    /**
     * @return array
     */
    private function getScalarValues()
    {
        $user = $this->getUser();
        return [
            'matches' => ['cnt' => 1, 'cnt_lucky' => 2],
            'userId'  => $user->getId(),
            'playConfigId' => 1
        ];
    }
}