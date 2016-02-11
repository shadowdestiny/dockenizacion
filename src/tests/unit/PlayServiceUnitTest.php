<?php


namespace tests\unit;


use EuroMillions\shared\config\Namespaces;
use EuroMillions\web\entities\Bet;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\vo\CastilloBetId;
use EuroMillions\web\vo\CreditCard;
use EuroMillions\web\vo\EuroMillionsLine;
use EuroMillions\web\vo\EuroMillionsLuckyNumber;
use EuroMillions\web\vo\EuroMillionsRegularNumber;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\vo\UserId;
use Money\Currency;
use Money\Money;
use Prophecy\Argument;
use tests\base\EuroMillionsResultRelatedTest;
use tests\base\LotteryValidationCastilloRelatedTest;
use tests\base\UnitTestBase;
use tests\helpers\mothers\CreditCardChargeMother;
use tests\helpers\mothers\CreditCardMother;
use tests\helpers\mothers\OrderMother;
use tests\helpers\mothers\UserMother;

class PlayServiceUnitTest extends UnitTestBase
{

    use EuroMillionsResultRelatedTest;
    use LotteryValidationCastilloRelatedTest;

    private $playConfigRepository_double;

    private $euroMillionsDrawRepository_double;

    private $lotteryDrawRepository_double;

    private $lotteryDataService_double;

    private $betRepository_double;

    private $playStorageStrategy_double;

    private $orderStorageStrategy_double;

    private $userRepository_double;

    private $authService_double;

    private $lotteryValidation_double;

    private $logValidationApi_double;

    private $cartService_double;

    private $walletService_double;

    private $card_payment_provider;

    private $betService_double;

    protected function getEntityManagerStubExtraMappings()
    {
        return [
            Namespaces::ENTITIES_NS . 'PlayConfig' => $this->playConfigRepository_double,
            Namespaces::ENTITIES_NS . 'EuroMillionsDraw' => $this->euroMillionsDrawRepository_double,
            Namespaces::ENTITIES_NS . 'Lottery' => $this->lotteryDrawRepository_double,
            Namespaces::ENTITIES_NS . 'Bet' => $this->betRepository_double,
            Namespaces::ENTITIES_NS . 'User' => $this->userRepository_double,
            Namespaces::ENTITIES_NS . 'LogValidationApi' => $this->logValidationApi_double
        ];
    }

    public function setUp()
    {
        $this->playConfigRepository_double = $this->getRepositoryDouble('PlayConfigRepository');
        $this->lotteryDataService_double = $this->getServiceDouble('LotteriesDataService');
        $this->betRepository_double = $this->getRepositoryDouble('BetRepository');
        $this->lotteryDrawRepository_double = $this->getRepositoryDouble('EuroMillions\web\entities\Lottery');
        $this->playStorageStrategy_double = $this->getInterfaceWebDouble('IPlayStorageStrategy');
        $this->orderStorageStrategy_double = $this->getInterfaceWebDouble('IPlayStorageStrategy');
        $this->userRepository_double = $this->getRepositoryDouble('UserRepository');
        $this->logValidationApi_double = $this->getRepositoryDouble('LogValidationApiRepository');
        $this->authService_double = $this->getServiceDouble('AuthService');
        $this->lotteryValidation_double = $this->prophesize('EuroMillions\web\services\external_apis\LotteryValidationCastilloApi');
        $this->cartService_double = $this->getServiceDouble('CartService');
        $this->walletService_double = $this->getServiceDouble('WalletService');
        $this->card_payment_provider = $this->getInterfaceWebDouble('ICardPaymentProvider');
        $this->betService_double = $this->getServiceDouble('BetService');
        parent::setUp();
    }

    /**
     * method play
     * when called
     * should returnServiceActionResultTrue
     */
    public function test_play_called_returnServiceActionResultTrue()
    {
        $expected = new ActionResult(true);
        $actual = $this->exerciseTemporarilyStorePlay($expected);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method play
     * when calledWithUserWithoutBalance
     * should returnServiceActionResultFalse
     */
    public function test_play_calledWithUserWithoutBalance_returnServiceActionResultFalse()
    {
        $this->markTestSkipped();
        $user = UserMother::aUserWithNoMoney()->build();
        $sut = $this->getSut();
        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush(Argument::any())->shouldNotBeCalled();
        $this->stubEntityManager($entityManager_stub);
        $actual = $sut->play($user);
        $this->assertEquals(false,$actual->success());
    }

    /**
     * method play
     * when calledAndPlayIsStored
     * should removeKeyAndReturnServiceActionResultTrue
     */
    public function test_play_calledAndPlayIsStored_removeKeyAndReturnServiceActionResultTrue()
    {
        $expected = new ActionResult(true);
        $actual = $this->exerciseTemporarilyStorePlay($expected);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method play
     * when calledAndRemoveKeyFromStorage
     * should returnServiceActionResultFalse
     */
    public function test_play_calledAndPlayIsNotStored_returnServiceActionResultFalse()
    {
        $expected = new ActionResult(false);
        $actual = $this->exerciseTemporarilyStorePlay($expected);
        $this->assertEquals($expected,$actual);
    }


    /**
     * method play
     * when calledAndPassKeyInvalidToSearchInStorage
     * should returnServiceActionResultFalse
     */
    public function test_play_calledAndPassKeyInvalidToSearchInStorage_returnServiceActionResultFalse()
    {
        $this->markTestSkipped();
        $expected = new ActionResult(false,'The search key doesn\'t exist');
        $user = $this->getUser();
        $sut = $this->getSut();
        $this->playStorageStrategy_double->findByKey($user->getId()->id())->willReturn(null);
        $actual = $sut->play($user);
        $this->assertEquals($expected,$actual);
    }



    /**
     * method getPlaysFromTemporarilyStorage
     * when calledAndPassKeyValid
     * should returnServiceActionResultTrueWithProperlyData
     */
    public function test_getPlaysFromTemporarilyStorage_calledAndPassKeyValid_returnServiceActionResultTrueWithProperlyData()
    {
        $string_json = '{"drawDays":"1","startDrawDate":"05 Feb 2016","lastDrawDate":"2016-02-05 00:00:00","frequency":"1","amount":null,"regular_numbers":null,"lucky_numbers":null,"euroMillionsLines":{"bets":[{"regular":[3,8,11,16,44],"lucky":[3,5]},{"regular":[6,17,37,38,48],"lucky":[1,5]}]},"numbers":null,"threshold":null,"num_weeks":0}';
        $play_config = $this->exercisePlayConfigFromJson($string_json);
        $expected = new ActionResult(true,$play_config);
        $user = $this->getUser();
        $sut = $this->getSut();
        $this->playStorageStrategy_double->findByKey($user->getId()->id())->willReturn(new ActionResult(true,$string_json));
        $actual = $sut->getPlaysFromTemporarilyStorage($user);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method temporarilyStorePlay
     * when called
     * should returnServiceActionResultTrueWhenStore
     */
    public function test_temporarilyStorePlay_called_returnServiceActionResultTrueWhenStore()
    {
        $expected = new ActionResult(true);
        $actual = $this->exerciseTemporarilyStorePlay($expected);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method temporarilyStorePlay
     * when called
     * should returnServiceActionResultFalseWhenTryStore
     */
    public function test_temporarilyStorePlay_called_returnServiceActionResultFalseWhenTryStore()
    {
        $expected = new ActionResult(false);
        $actual = $this->exerciseTemporarilyStorePlay($expected);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method getPlayConfigToBet
     * when called
     * should returnServiceActionResultTrueWithProperData
     */
    public function test_getPlayConfigToBet_called_returnServiceActionResultTrueWithProperData()
    {
        $expected = 1;
        $date = new \DateTime('2015-10-05');
        $sut = $this->getSut();
        $this->playConfigRepository_double->getPlayConfigsByDrawDayAndDate($date)->willReturn(true);
        $actual = $sut->getPlaysConfigToBet($date);
        $this->assertGreaterThanOrEqual($expected,count($actual->getValues()));
    }

    /**
     * method getPlayConfigToBet
     * when called
     * should transformDateTimeToStringAndPassItToRepository
     */
    public function test_getPlayConfigToBet_called_transformDateTimeToStringAndPassItToRepository()
    {
        $expected = '2015-10-10';
        $date = new \DateTime($expected);
        $this->playConfigRepository_double->getPlayConfigsByDrawDayAndDate($date)->shouldBeCalled();
        $sut = $this->getSut();
        $sut->getPlaysConfigToBet($date);
    }


    /**
     * method getPlayConfigWithLongEnded
     * when called
     * should returnActionResultTrueWithCollection
     */
    public function test_getPlayConfigWithLongEnded_called_returnActionResultTrueWithCollection()
    {
        $expected = new ActionResult(true, $this->getPlayConfig());
        $date = new \DateTime('2015-11-25');
        $this->playConfigRepository_double->getPlayConfigsLongEnded($date)->willReturn($this->getPlayConfig());
        $sut = $this->getSut();
        $actual = $sut->getPlayConfigWithLongEnded($date);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method getPlayConfigWithLongEnded
     * when called
     * should returnActionResultFalse
     */
    public function test_getPlayConfigWithLongEnded_called_returnActionResultFalse()
    {
        $expected = new ActionResult(false);
        $date = new \DateTime('2015-11-25');
        $this->playConfigRepository_double->getPlayConfigsLongEnded($date)->willReturn(false);
        $sut = $this->getSut();
        $actual = $sut->getPlayConfigWithLongEnded($date);
        $this->assertEquals($expected,$actual);
    }


    /**
     * method play
     * when calledWithAUserWithOrderWithABetForNextDraw
     * should getPayConfigurationFromOrder
     */
    public function test_play_calledWithAUserWithOrderWithABetForNextDraw_getPayConfigurationFromOrder()
    {
        $this->markTestSkipped();
        $user = UserMother::aUserWith50Eur()->build();
        $order = OrderMother::aJustOrder()->build();
        $this->orderStorageStrategy_double->findByKey($user->getId())->willReturn($order->toJsonData());
        $this->cartService_double->get($user->getId())->willReturn(new ActionResult(true));
        $sut = $this->getSut();
        $sut->play($user->getId());
    }

    /**
     * method play
     * when calledWithAUserWithOrderWithABetForNextDraw
     * should chargeCreditCardWhenTheresNotEnoughFundsOnWallet
     */
    public function test_play_calledWithAUserWithOrderWithABetForNextDraw_chargeCreditCardWhenTheresNotEnoughFundsOnWallet()
    {
        $user = UserMother::aUserWith50Eur()->build();
        $order = OrderMother::aJustOrder()->build();
        $credit_card = CreditCardMother::aValidCreditCard();
        $this->exercisePlayWallet($user, $order, $credit_card);
        $this->betService_double->validation(Argument::any(),Argument::any())->willReturn(new ActionResult(true));
        $sut = $this->getSut();
        $actual = $sut->play($user->getId(),null,$credit_card);
        $this->assertEquals(new ActionResult(true),$actual);
    }

    /**
     * method play
     * when calledWithAUserWithOrderWithABetForNextDraw
     * should chargeCreditCardWhenTheresAddedFunds
     */
    public function test_play_calledWithAUserWithOrderWithABetForNextDraw_chargeCreditCardWhenTheresAddedFunds()
    {
        $user = UserMother::aUserWith50Eur()->build();
        $funds_amount_to_charged = new Money(2000, new Currency('EUR'));
        $order = OrderMother::aJustOrder()->build();
        $order->addFunds($funds_amount_to_charged);
        $expected = new ActionResult(true);
        $credit_card = CreditCardMother::aValidCreditCard();
        $this->exercisePlayWallet($user, $order, $credit_card);
        $this->betService_double->validation(Argument::any(),Argument::any())->willReturn(new ActionResult(true));
        $sut = $this->getSut();
        $actual = $sut->play($user->getId(), $funds_amount_to_charged, $credit_card);
        $this->assertEquals($expected, $actual);
    }

    /**
     * method play
     * when calledWithAUserWithOrderWithABetForNextDraw
     * should notChargeCreditCardWhenTheresEnoughFundsOnWalletAndNotAddedFunds
     */
    public function test_play_calledWithAUserWithOrderWithABetForNextDraw_notChargeCreditCardWhenTheresEnoughFundsOnWalletAndNotAddedFunds()
    {
        $user = UserMother::aUserWith50Eur()->build();
        $order = OrderMother::aJustOrder()->build();
        $expected = new ActionResult(true);
        $userId = $user->getId();
        list($play_config, $euromillions_draw) = $this->getPlayConfigAndEuroMillionsDraw();
        $euromillions_draw->setDrawDate(new \DateTime('2016-02-05 20:00:00'));
        $this->userRepository_double->find(['id' => $user->getId()])->willReturn($user);
        $this->orderStorageStrategy_double->findByKey($user->getId())->willReturn($order->toJsonData());
        $this->cartService_double->get($user->getId())->willReturn(new ActionResult(true, $order));
        $this->lotteryDataService_double->getNextDrawByLottery('EuroMillions')->willReturn(new ActionResult(true, $euromillions_draw));
        $this->betService_double->validation(Argument::any(),Argument::any())->willReturn(new ActionResult(true));
        $sut = $this->getSut();
        $actual = $sut->play($userId);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method play
     * when calledWithABetForNextDraw
     * should validateAgainstCastillo
     */
    public function test_play_calledWithABetForNextDraw_validateAgainstCastillo()
    {
        $user = UserMother::aUserWith50Eur()->build();
        $order = OrderMother::aJustOrder()->build();
        $credit_card = CreditCardMother::aValidCreditCard();
        $this->exercisePlayWallet($user,$order,$credit_card);
        $expected = new ActionResult(true);
        $sut = $this->getSut();
        $this->betService_double->validation(Argument::any(),Argument::any())->willReturn(new ActionResult(true));
        $actual = $sut->play($user->getId(),null, $credit_card);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method play
     * when calledWithoutBetsForNextDraw
     * should notValidateAgainstCastillo
     */
    public function test_play_calledWithoutBetsForNextDraw_notValidateAgainstCastillo()
    {
        $user = UserMother::aUserWith50Eur()->build();
        $order = OrderMother::aJustOrder()->build();
        $credit_card = CreditCardMother::aValidCreditCard();
        $this->exercisePlayWallet($user,$order,$credit_card);
        $this->userRepository_double->find(Argument::any())->willReturn($this->getUser());
        $this->lotteryDataService_double->getNextDateDrawByLottery('EuroMillions', Argument::any())->willReturn(new \DateTime('2016-10-04'));
        $this->betService_double->validation(Argument::any(),Argument::any())->willReturn(new ActionResult(false));
        $sut = $this->getSut();
        $actual = $sut->play($user->getId(),null, $credit_card);
        $this->assertEquals(new ActionResult(false),$actual);
    }

    /**
     * method play
     * when calledWithoutBetsForNextDraw
     * should notChargeCreditCardIfTheresNotAddedFunds
     */
    public function test_play_calledWithoutBetsForNextDraw_notChargeCreditCardIfTheresNotAddedFunds()
    {

    }

    /**
     * method play
     * when calledWithoutBetsForNextDraw
     * should chargeCreditCardIfTheresAddedFunds
     */
    public function test_play_calledWithoutBetsForNextDraw_chargeCreditCardIfTheresAddedFunds()
    {

    }

    /**
     * method getPlaysFromGuestUserAndSwitchUser
     * when called
     * should returnActionResultTrueWithPlaysGuestUser
     */
    public function test_getPlaysFromGuestUserAndSwitchUser_called_returnActionResultTrueWithPlaysGuestUser()
    {
        $string_json = '{"drawDays":"1","startDrawDate":"05 Feb 2016","lastDrawDate":"2016-02-05 00:00:00","frequency":"1","amount":null,"regular_numbers":null,"lucky_numbers":null,"euroMillionsLines":{"bets":[{"regular":[3,8,11,16,44],"lucky":[3,5]},{"regular":[6,17,37,38,48],"lucky":[1,5]}]},"numbers":null,"threshold":null,"num_weeks":0}';
        $play_config = $this->exercisePlayConfigFromJson($string_json);
        $expected = new ActionResult(true,$play_config);
        $user_id = new UserId('9098299B-14AC-4124-8DB0-19571EDABE55');
        $current_user_id = UserId::create();
        $this->userRepository_double->find($user_id->id())->willReturn($this->getUser());
        $this->playStorageStrategy_double->findByKey($user_id->id())->willReturn(new ActionResult(true,$string_json));
        $this->playStorageStrategy_double->save($string_json,$current_user_id)->shouldBeCalled();
        $this->playStorageStrategy_double->findByKey($current_user_id)->willReturn(new ActionResult(true,$string_json));
        $sut = $this->getSut();
        $actual = $sut->getPlaysFromGuestUserAndSwitchUser($user_id, $current_user_id);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method getTotalPriceFromPlay
     * when called
     * should returnActionResultTrueWithTotalPriceFromPlay
     */
    public function test_getTotalPriceFromPlay_called_returnActionResultTrueWithTotalPriceFromPlay()
    {
        $string_json = '{"drawDays":"1","startDrawDate":"05 Feb 2016","lastDrawDate":"2016-02-05 00:00:00","frequency":"1","amount":null,"regular_numbers":null,"lucky_numbers":null,"euroMillionsLines":{"bets":[{"regular":[3,8,11,16,44],"lucky":[3,5]},{"regular":[6,17,37,38,48],"lucky":[1,5]}]},"numbers":null,"threshold":null,"num_weeks":0}';
        $expected = new Money(5000, new Currency('EUR'));
        $play_config = $this->exercisePlayConfigFromJson($string_json);
        $single_bet_price = new Money(2500, new Currency('EUR'));
        $this->lotteryDataService_double->getSingleBetPriceByLottery('EuroMillions')->willReturn($single_bet_price);
        $sut = $this->getSut();
        $actual = $sut->getTotalPriceFromPlay($play_config,$single_bet_price);
        $this->assertEquals($expected,$actual);
    }


    private function exerciseTemporarilyStorePlay($expected)
    {
        $user = $this->getUser();
        $playForm = $this->getPlayFormToStorage();
        $this->playStorageStrategy_double->saveAll($playForm,$user->getId())->willReturn($expected);
        $sut = $this->getSut();
        return $sut->temporarilyStorePlay($playForm,$user->getId());

    }

    private function getSut(){
        $sut = $this->getDomainServiceFactory()->getPlayService($this->lotteryDataService_double->reveal(),
                                                                $this->playStorageStrategy_double->reveal(),
                                                                $this->orderStorageStrategy_double->reveal(),
                                                                $this->cartService_double->reveal(),
                                                                $this->walletService_double->reveal(),
                                                                $this->card_payment_provider->reveal(),
                                                                $this->betService_double->reveal()
                                                                );
        return $sut;
    }

    /**
     * @param string $currency
     * @return User
     */
    private function getUser()
    {
        return UserMother::aUserWith500Eur()->build();
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
                'line' => [$euroMillionsLine]
            ]
        );
        return [$playConfig,$euroMillionsDraw];
    }

    /**
     * @return Bet
     */
    protected function getBetForValidation()
    {
        $play_config = $this->getPlayConfig();
        $euromillions_draw = new EuroMillionsDraw();
        $bet_id_castillo = CastilloBetId::create();
        $bet = new Bet($play_config, $euromillions_draw);
        $bet->setCastilloBet($bet_id_castillo);
        return $bet;
    }

    protected function getPlayConfig()
    {
        $reg = [7,16,17,22,15];
        $regular_numbers = [];
        foreach($reg as $regular_number){
            $regular_numbers[] = new EuroMillionsRegularNumber($regular_number);
        }
        $luck = [7,1];
        $lucky_numbers = [];
        foreach($luck as $lucky_number){
            $lucky_numbers[] = new EuroMillionsLuckyNumber($lucky_number);
        }

        $playConfig = new PlayConfig();
        $line = new EuroMillionsLine($regular_numbers,$lucky_numbers);
        $playConfig->setLine($line);
        return $playConfig;
    }

    /**
     * @param $expected
     * @return array
     */
    private function exerciseValidationBet($expected)
    {
        list($playConfig, $euroMillionsDraw) = $this->getPlayConfigAndEuroMillionsDraw();
        $this->userRepository_double->find(Argument::any())->willReturn($this->getUser());
        $this->lotteryDataService_double->getNextDateDrawByLottery('EuroMillions', Argument::any())->willReturn(new \DateTime('2016-10-04'));
        $this->callValidationApi($expected);
        return array($playConfig, $euroMillionsDraw);
    }

    /**
     * @param $string_json
     */
    private function exercisePlayConfigFromJson($string_json)
    {
        $form_decode = json_decode($string_json);
        $bets = [];
        foreach($form_decode->euroMillionsLines->bets as $bet) {
            $bets[] = $bet;
        }
        $playConfig = new PlayConfig();
        $playConfig->formToEntity($this->getUser(),$string_json,$bets);
        return $playConfig;
    }

    /**
     * @param $expected
     */
    private function callValidationApi($expected)
    {
        $this->lotteryValidation_double->validateBet(Argument::type($this->getEntitiesToArgument('Bet')),
            Argument::type($this->getInterfacesToArgument('ICypherStrategy')),
            Argument::type($this->getVOToArgument('CastilloCypherKey')),
            Argument::type($this->getVOToArgument('CastilloTicketId')),
            Argument::type('\DateTime'),Argument::type($this->getVOToArgument('EuroMillionsLine')))->willReturn(new ActionResult($expected));
    }

    /**
     * @param $user
     * @param $order
     * @param $credit_card
     */
    private function exercisePlayWallet($user, $order, $credit_card)
    {
        /** @var EuroMillionsDraw $euromillions_draw */
        list($play_config,$euromillions_draw) = $this->getPlayConfigAndEuroMillionsDraw();
        $euromillions_draw->setDrawDate(new \DateTime('2016-02-05 20:00:00'));
        $this->userRepository_double->find(['id' => $user->getId()])->willReturn($user);
        $this->orderStorageStrategy_double->findByKey($user->getId())->willReturn($order->toJsonData());
        $this->cartService_double->get($user->getId())->willReturn(new ActionResult(true, $order));
        $this->walletService_double->rechargeWithCreditCard($this->card_payment_provider->reveal(), $credit_card, $user, $order->getCreditCardCharge())->willReturn(new ActionResult(true));
        $this->lotteryDataService_double->getNextDrawByLottery('EuroMillions')->willReturn(new ActionResult(true, $euromillions_draw));
    }

    private function exercisePayAndValidation()
    {
        $this->logValidationApi_double->add(Argument::type($this->getEntitiesToArgument('LogValidationApi')))->shouldBeCalled();
        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush(Argument::type($this->getEntitiesToArgument('LogValidationApi')))->shouldNotBeCalled();
        $this->logValidationApi_double->add(Argument::any())->shouldBeCalled();
        $entityManager_stub->flush(Argument::any())->shouldBeCalled();
        $this->userRepository_double->add(Argument::type('EuroMillions\web\entities\User'))->shouldBeCalled();
        $this->playConfigRepository_double->add(Argument::type('EuroMillions\web\entities\PlayConfig'))->shouldBeCalled();
        $entityManager_stub->flush()->shouldBeCalled();
    }
}