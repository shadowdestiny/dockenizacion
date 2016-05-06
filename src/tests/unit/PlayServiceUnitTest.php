<?php


namespace EuroMillions\tests\unit;


use EuroMillions\shared\config\Namespaces;
use EuroMillions\web\components\UserId;
use EuroMillions\web\entities\Bet;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\services\PlayService;
use EuroMillions\web\vo\CastilloBetId;
use EuroMillions\web\vo\enum\TransactionType;
use EuroMillions\web\vo\EuroMillionsLine;
use EuroMillions\web\vo\EuroMillionsLuckyNumber;
use EuroMillions\web\vo\EuroMillionsRegularNumber;
use EuroMillions\shared\vo\results\ActionResult;
use Money\Currency;
use Money\Money;
use Prophecy\Argument;
use EuroMillions\tests\base\EuroMillionsResultRelatedTest;
use EuroMillions\tests\base\LotteryValidationCastilloRelatedTest;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\CreditCardMother;
use EuroMillions\tests\helpers\mothers\OrderMother;
use EuroMillions\tests\helpers\mothers\UserMother;

class PlayServiceUnitTest extends UnitTestBase
{

    use EuroMillionsResultRelatedTest;
    use LotteryValidationCastilloRelatedTest;

    private $playConfigRepository_double;

    private $euroMillionsDrawRepository_double;

    private $lotteryDrawRepository_double;

    private $lotteryService_double;

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
            Namespaces::ENTITIES_NS . 'PlayConfig'       => $this->playConfigRepository_double,
            Namespaces::ENTITIES_NS . 'EuroMillionsDraw' => $this->euroMillionsDrawRepository_double,
            Namespaces::ENTITIES_NS . 'Lottery'          => $this->lotteryDrawRepository_double,
            Namespaces::ENTITIES_NS . 'Bet'              => $this->betRepository_double,
            Namespaces::ENTITIES_NS . 'User'             => $this->userRepository_double,
            Namespaces::ENTITIES_NS . 'LogValidationApi' => $this->logValidationApi_double
        ];
    }

    public function setUp()
    {
        $this->playConfigRepository_double = $this->getRepositoryDouble('PlayConfigRepository');
        $this->lotteryService_double = $this->getServiceDouble('LotteryService');
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
        $this->assertEquals($expected, $actual);
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
        $this->assertEquals($expected, $actual);
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
        $this->assertEquals($expected, $actual);
    }


    /**
     * method getPlaysFromTemporarilyStorage
     * when calledAndPassKeyValid
     * should returnServiceActionResultTrueWithProperlyData
     */
    public function test_getPlaysFromTemporarilyStorage_calledAndPassKeyValid_returnServiceActionResultTrueWithProperlyData()
    {
        $string_json = '{"play_config":[{"drawDays":"2","startDrawDate":"16 Feb 2016","lastDrawDate":"2016-02-16 00:00:00","frequency":"1","amount":null,"regular_numbers":null,"lucky_numbers":null,"euroMillionsLines":{"bets":[{"regular":[16,18,20,21,32],"lucky":[4,8]}]},"numbers":null,"threshold":null,"num_weeks":0},{"drawDays":"2","startDrawDate":"16 Feb 2016","lastDrawDate":"2016-02-16 00:00:00","frequency":"1","amount":null,"regular_numbers":null,"lucky_numbers":null,"euroMillionsLines":{"bets":[{"regular":[3,22,23,30,44],"lucky":[7,9]}]},"numbers":null,"threshold":null,"num_weeks":0},{"drawDays":"2","startDrawDate":"16 Feb 2016","lastDrawDate":"2016-02-16 00:00:00","frequency":"1","amount":null,"regular_numbers":null,"lucky_numbers":null,"euroMillionsLines":{"bets":[{"regular":[31,37,39,44,47],"lucky":[4,10]}]},"numbers":null,"threshold":null,"num_weeks":0},{"drawDays":"2","startDrawDate":"16 Feb 2016","lastDrawDate":"2016-02-16 00:00:00","frequency":"1","amount":null,"regular_numbers":null,"lucky_numbers":null,"euroMillionsLines":{"bets":[{"regular":[25,31,33,38,47],"lucky":[2,6]}]},"numbers":null,"threshold":null,"num_weeks":0}]}';
        $play_config = $this->exercisePlayConfigFromJson($string_json);
        $expected = new ActionResult(true, $play_config);
        $user = $this->getUser();
        $sut = $this->getSut();
        $this->playStorageStrategy_double->findByKey($user->getId())->willReturn(new ActionResult(true, $string_json));
        $actual = $sut->getPlaysFromTemporarilyStorage($user);
        $this->assertEquals($expected, $actual);
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
        $this->assertEquals($expected, $actual);
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
        $this->assertEquals($expected, $actual);
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
        $this->assertGreaterThanOrEqual($expected, count($actual->getValues()));
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
        $this->assertEquals($expected, $actual);
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
        $this->assertEquals($expected, $actual);
    }


    /**
     * method play
     * when calledWithAUserWithOrderWithABetForNextDraw
     * should chargeCreditCardWhenTheresNotEnoughFundsOnWallet
     */
    public function test_play_calledWithAUserWithOrderWithABetForNextDraw_chargeCreditCardWhenTheresNotEnoughFundsOnWallet()
    {
        $draw_date = new \DateTime('2016-02-16 20:00:00');
        $user = UserMother::aUserWith50Eur()->build();
        $order = OrderMother::aJustOrder()->build();
        $credit_card = CreditCardMother::aValidCreditCard();
        $this->exercisePlayWallet($user, $order, $credit_card,$draw_date);
        $this->betService_double->validation(Argument::any(), Argument::any(),Argument::any())->willReturn(new ActionResult(true));
        $entityManager_double = $this->getEntityManagerDouble();
        $this->playConfigRepository_double->add(Argument::type('EuroMillions\web\entities\PlayConfig'))->shouldBeCalled();
        $entityManager_double->flush(Argument::type('EuroMillions\web\entities\PlayConfig'))->shouldBeCalled();
        $this->walletService_double->payWithWallet($user,Argument::type('EuroMillions\web\entities\PlayConfig'), TransactionType::TICKET_PURCHASE, Argument::type('array'))->shouldBeCalled();
        $sut = $this->getSut();
        $actual = $sut->play($user->getId(), null, $credit_card);
        $this->assertEquals(new ActionResult(true, $order), $actual);
    }

    /**
     * method play
     * when calledWithAUserWithOrderWithABetForNextDraw
     * should chargeCreditCardWhenTheresAddedFunds
     */
    public function test_play_calledWithAUserWithOrderWithABetForNextDraw_chargeCreditCardWhenTheresAddedFunds()
    {
        $draw_date = new \DateTime('2016-02-16 20:00:00');
        $user = UserMother::aUserWith50Eur()->build();
        $funds_amount_to_charged = new Money(2000, new Currency('EUR'));
        $order = OrderMother::aJustOrder()->build();
        $order->addFunds($funds_amount_to_charged);
        $expected = new ActionResult(true, $order);
        $credit_card = CreditCardMother::aValidCreditCard();
        $this->exercisePlayWallet($user, $order, $credit_card,$draw_date);
        $this->betService_double->validation(Argument::any(), Argument::any(), Argument::any())->willReturn(new ActionResult(true));
        $entityManager_double = $this->getEntityManagerDouble();
        $this->playConfigRepository_double->add(Argument::type('EuroMillions\web\entities\PlayConfig'))->shouldBeCalled();
        $entityManager_double->flush(Argument::type('EuroMillions\web\entities\PlayConfig'))->shouldBeCalled();
        $this->walletService_double->payWithWallet($user,Argument::type('EuroMillions\web\entities\PlayConfig'), TransactionType::TICKET_PURCHASE,Argument::type('array'))->shouldBeCalled();
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
        $user = UserMother::aUserWith500Eur()->build();
        $order = OrderMother::aJustOrder()->build();
        $expected = new ActionResult(true, $order);
        $userId = $user->getId();
        list($play_config, $euromillions_draw) = $this->getPlayConfigAndEuroMillionsDraw();
        $euromillions_draw->setDrawDate(new \DateTime('2016-02-05 20:00:00'));
        $lottery = $this->prepareLotteryEntity('EuroMillions');
        $this->lotteryService_double->getLotteryConfigByName('EuroMillions')->willReturn($lottery);
        $this->userRepository_double->find(['id' => $user->getId()])->willReturn($user);
        $this->orderStorageStrategy_double->findByKey($user->getId())->willReturn($order->toJsonData());
        $this->cartService_double->get($user->getId())->willReturn(new ActionResult(true, $order));
        $this->lotteryService_double->getNextDrawByLottery('EuroMillions')->willReturn(new ActionResult(true, $euromillions_draw));
        $entityManager_double = $this->getEntityManagerDouble();
        $this->betService_double->validation(Argument::any(), Argument::any(), Argument::any())->willReturn(new ActionResult(true));
        $this->playConfigRepository_double->add(Argument::type('EuroMillions\web\entities\PlayConfig'))->shouldBeCalled();
        $entityManager_double->flush(Argument::type('EuroMillions\web\entities\PlayConfig'))->shouldBeCalled();
        $sut = $this->getSut();
        $actual = $sut->play($userId);
        $this->assertEquals($expected, $actual);
    }

    /**
     * method play
     * when calledWithABetForNextDraw
     * should validateAgainstCastillo
     */
    public function test_play_calledWithABetForNextDraw_validateAgainstCastillo()
    {
        $draw_date = new \DateTime('2016-02-16 20:00:00');
        $user = UserMother::aUserWith50Eur()->build();
        $order = OrderMother::aJustOrder()->build();
        $credit_card = CreditCardMother::aValidCreditCard();
        $this->exercisePlayWallet($user, $order, $credit_card,$draw_date);
        $expected = new ActionResult(true, $order);
        $sut = $this->getSut();
        $this->betService_double->validation(Argument::any(), Argument::any(),Argument::any())->willReturn(new ActionResult(true));
        $entityManager_double = $this->getEntityManagerDouble();
        $this->playConfigRepository_double->add(Argument::type('EuroMillions\web\entities\PlayConfig'))->shouldBeCalledTimes(4);
        $entityManager_double->flush(Argument::type('EuroMillions\web\entities\PlayConfig'))->shouldBeCalled();
        $this->walletService_double->payWithWallet($user,Argument::type('EuroMillions\web\entities\PlayConfig'), TransactionType::TICKET_PURCHASE, Argument::type('array'))->shouldBeCalled();
        $actual = $sut->play($user->getId(), null, $credit_card);
        $this->assertEquals($expected, $actual);
    }

    /**
     * method play
     * when calledWithoutBetsForNextDraw
     * should notValidateAgainstCastillo
     */
    public function test_play_calledWithoutBetsForNextDraw_notValidateAgainstCastillo()
    {
        $this->markTestSkipped('error en este test');
        $draw_date = new \DateTime('2016-02-05 20:00:00');
        $user = UserMother::aUserWith50Eur()->build();
        $order = OrderMother::aJustOrder()->build();
        $credit_card = CreditCardMother::aValidCreditCard();
        $this->exercisePlayWallet($user, $order, $credit_card, $draw_date);
        $entityManager_double = $this->getEntityManagerDouble();
        $this->playConfigRepository_double->add(Argument::type('EuroMillions\web\entities\PlayConfig'))->shouldBeCalledTimes(4);
        $entityManager_double->flush(Argument::type('EuroMillions\web\entities\PlayConfig'))->shouldBeCalled();
        $this->betService_double->validation(Argument::any(), Argument::any(), Argument::any())->willReturn(new ActionResult(true));
        $dataTransaction = [
            'lottery_id' => 1,
            'numBets' => count($user->getPlayConfig()),
            'feeApplied' => $order->getCreditCardCharge()->getIsChargeFee()
        ];
        //$this->walletService_double->payWithWallet($user,Argument::type('EuroMillions\web\entities\PlayConfig'), TransactionType::TICKET_PURCHASE, TransactionType::TICKET_PURCHASE, $dataTransaction)->shouldBeCalled();
        $sut = $this->getSut();
        $actual = $sut->play($user->getId(), null, $credit_card);
        $this->assertEquals(new ActionResult(true, $order), $actual);
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
     * method removeStorePlay
     * when calledPassingAValidUserId
     * should removePlayFromStorage
     */
    public function test_removeStorePlay_calledPassingAValidUserId_removePlayFromStorage()
    {
        $user = UserMother::anAlreadyRegisteredUser()->build();
        $this->playStorageStrategy_double->delete($user->getId())->shouldBeCalled();
        $sut = $this->getSut();
        $sut->removeStorePlay($user->getId());
    }

    /**
     * method removeStoreOrder
     * when calledPassingAValidUserID
     * should removeOrderFromStorage
     */
    public function test_removeStoreOrder_calledPassingAValidUserID_removeOrderFromStorage()
    {
        $user = UserMother::anAlreadyRegisteredUser()->build();
        $this->orderStorageStrategy_double->delete($user->getId())->shouldBeCalled();
        $sut = $this->getSut();
        $sut->removeStoreOrder($user->getId());
    }

    /**
     * method getPlaysFromGuestUserAndSwitchUser
     * when called
     * should returnActionResultTrueWithPlaysGuestUser
     */
    public function test_getPlaysFromGuestUserAndSwitchUser_called_returnActionResultTrueWithPlaysGuestUser()
    {
        $string_json = '{"play_config":[{"drawDays":"2","startDrawDate":"16 Feb 2016","lastDrawDate":"2016-02-16 00:00:00","frequency":"1","amount":null,"regular_numbers":null,"lucky_numbers":null,"euroMillionsLines":{"bets":[{"regular":[16,18,20,21,32],"lucky":[4,8]}]},"numbers":null,"threshold":null,"num_weeks":0},{"drawDays":"2","startDrawDate":"16 Feb 2016","lastDrawDate":"2016-02-16 00:00:00","frequency":"1","amount":null,"regular_numbers":null,"lucky_numbers":null,"euroMillionsLines":{"bets":[{"regular":[3,22,23,30,44],"lucky":[7,9]}]},"numbers":null,"threshold":null,"num_weeks":0},{"drawDays":"2","startDrawDate":"16 Feb 2016","lastDrawDate":"2016-02-16 00:00:00","frequency":"1","amount":null,"regular_numbers":null,"lucky_numbers":null,"euroMillionsLines":{"bets":[{"regular":[31,37,39,44,47],"lucky":[4,10]}]},"numbers":null,"threshold":null,"num_weeks":0},{"drawDays":"2","startDrawDate":"16 Feb 2016","lastDrawDate":"2016-02-16 00:00:00","frequency":"1","amount":null,"regular_numbers":null,"lucky_numbers":null,"euroMillionsLines":{"bets":[{"regular":[25,31,33,38,47],"lucky":[2,6]}]},"numbers":null,"threshold":null,"num_weeks":0}]}';
        $play_config = $this->exercisePlayConfigFromJson($string_json);
        $expected = new ActionResult(true, $play_config);
        $user_id = '9098299B-14AC-4124-8DB0-19571EDABE55';
        $current_user_id = UserId::create();
        $this->userRepository_double->find($current_user_id)->willReturn($this->getUser());
        $this->playStorageStrategy_double->findByKey($user_id)->willReturn(new ActionResult(true, $string_json));
        $this->playStorageStrategy_double->save($string_json, $current_user_id)->shouldBeCalled();
        $this->playStorageStrategy_double->findByKey($current_user_id)->willReturn(new ActionResult(true, $string_json));
        $sut = $this->getSut();
        $actual = $sut->getPlaysFromGuestUserAndSwitchUser($user_id, $current_user_id);
        $this->assertEquals($expected, $actual);
    }




    private function exerciseTemporarilyStorePlay($expected)
    {
        $user = $this->getUser();
        $playForm = $this->getPlayFormToStorage();
        $this->playStorageStrategy_double->saveAll($playForm, $user->getId())->willReturn($expected);
        $sut = $this->getSut();
        return $sut->temporarilyStorePlay($playForm, $user->getId());

    }

    private function getSut()
    {
        return new PlayService(
            $this->getEntityManagerRevealed(),
            $this->lotteryService_double->reveal(),
            $this->playStorageStrategy_double->reveal(),
            $this->orderStorageStrategy_double->reveal(),
            $this->cartService_double->reveal(),
            $this->walletService_double->reveal(),
            $this->card_payment_provider->reveal(),
            $this->betService_double->reveal()
        );
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
            'id'               => 1,
            'name'             => 'EuroMillions',
            'active'           => 1,
            'frequency'        => 'freq',
            'draw_time'        => 'draw',
            'single_bet_price' => new Money(23500, new Currency('EUR')),
        ]);
        $euroMillionsDraw->setLottery($lottery);
        $playConfig = new PlayConfig();
        $playConfig->initialize([
                'user' => $user,
                'line' => [$euroMillionsLine]
            ]
        );
        return [$playConfig, $euroMillionsDraw];
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
        $reg = [7, 16, 17, 22, 15];
        $regular_numbers = [];
        foreach ($reg as $regular_number) {
            $regular_numbers[] = new EuroMillionsRegularNumber($regular_number);
        }
        $luck = [7, 1];
        $lucky_numbers = [];
        foreach ($luck as $lucky_number) {
            $lucky_numbers[] = new EuroMillionsLuckyNumber($lucky_number);
        }

        $playConfig = new PlayConfig();
        $line = new EuroMillionsLine($regular_numbers, $lucky_numbers);
        $playConfig->setLine($line);
        return $playConfig;
    }


    /**
     * @param $string_json
     */
    private function exercisePlayConfigFromJson($string_json)
    {
        $form_decode = json_decode($string_json);
        $bets = [];
        foreach ($form_decode->play_config as $bet) {
            $playConfig = new PlayConfig();
            $playConfig->formToEntity($this->getUser(), $bet, $bet->euroMillionsLines);
            $bets[] = $playConfig;
        }
        return $bets;
    }

    /**
     * @param $user
     * @param $order
     * @param $credit_card
     */
    private function exercisePlayWallet($user, $order, $credit_card, $draw_date)
    {
        /** @var EuroMillionsDraw $euromillions_draw */
        list($play_config, $euromillions_draw) = $this->getPlayConfigAndEuroMillionsDraw();
        $lottery = $this->prepareLotteryEntity('EuroMillions');
        $euromillions_draw->setDrawDate($draw_date);
        $this->lotteryService_double->getLotteryConfigByName('EuroMillions')->willReturn($lottery);
        $this->userRepository_double->find(['id' => $user->getId()])->willReturn($user);
        $this->orderStorageStrategy_double->findByKey($user->getId())->willReturn($order->toJsonData());
        $this->cartService_double->get($user->getId())->willReturn(new ActionResult(true, $order));
        $this->walletService_double->payWithCreditCard($this->card_payment_provider->reveal(), $credit_card, $user, $order->getCreditCardCharge())->willReturn(new ActionResult(true));
        $this->lotteryService_double->getNextDrawByLottery('EuroMillions')->willReturn(new ActionResult(true, $euromillions_draw));
    }

    /**
     * @param $lottery_name
     * @return Lottery
     */
    protected function prepareLotteryEntity($lottery_name)
    {
        $lottery = new Lottery();
        $lottery->initialize([
            'id'        => 1,
            'name'      => $lottery_name,
            'active'    => 1,
            'frequency' => 'w0100100',
            'draw_time' => '20:00:00',
            'single_bet_price' => new Money(250,new Currency('EUR'))

        ]);
        return $lottery;
    }



}