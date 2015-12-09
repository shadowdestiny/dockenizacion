<?php


namespace tests\unit;


use EuroMillions\web\components\NullPasswordHasher;
use EuroMillions\shared\shareconfig\Namespaces;
use EuroMillions\web\entities\Bet;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\vo\CastilloBetId;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\EuroMillionsLine;
use EuroMillions\web\vo\EuroMillionsLuckyNumber;
use EuroMillions\web\vo\EuroMillionsRegularNumber;
use EuroMillions\web\vo\LastDrawDate;
use EuroMillions\web\vo\Password;
use EuroMillions\web\vo\PlayFormToStorage;
use EuroMillions\web\vo\ActionResult;
use EuroMillions\web\vo\UserId;
use Money\Currency;
use Money\Money;
use Prophecy\Argument;
use tests\base\EuroMillionsResultRelatedTest;
use tests\base\LotteryValidationCastilloRelatedTest;
use tests\base\UnitTestBase;

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

    private $userRepository_double;

    private $authService_double;

    private $lotteryValidation_double;

    private $logValidationApi_double;

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
        $this->userRepository_double = $this->getRepositoryDouble('UserRepository');
        $this->logValidationApi_double = $this->getRepositoryDouble('LogValidationApiRepository');
        $this->authService_double = $this->getServiceDouble('AuthService');
        $this->lotteryValidation_double = $this->prophesize('EuroMillions\web\services\external_apis\LotteryValidationCastilloApi');
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
        $expected = new ActionResult(false);
        $user = $this->getUser();
        $user->setBalance(new Money(0,new Currency('EUR')));
        $regular_numbers = [1, 2, 3, 4, 5];
        $lucky_numbers = [5, 8];
        $euroMillionsResult = new EuroMillionsLine($this->getRegularNumbers($regular_numbers),
            $this->getLuckyNumbers($lucky_numbers));
        $sut = $this->getSut();
        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush(Argument::any())->shouldNotBeCalled();
        $this->stubEntityManager($entityManager_stub);
        $actual = $sut->play($user,$euroMillionsResult);
        $this->assertEquals($expected,$actual);
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
        $expected = new ActionResult(false,'The search key doesn\'t exist');
        $user = $this->getUser();
        $sut = $this->getSut();
        $this->playStorageStrategy_double->findByKey($user->getId()->id())->willReturn(null);
        $actual = $sut->play($user);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method bet
     * when calledWhenTheresNoBetInDB
     * should returnServiceActionResultTrueAndCreateNewBet
     */
    public function test_bet_calledWhenTheresNoBetInDB_returnServiceActionResultTrueAndCreateNewBet()
    {
        $expected = new ActionResult(true);
        list($playConfig,$euroMillionsDraw) = $this->getPlayConfigAndEuroMillionsDraw();
        $entityManager_stub = $this->getEntityManagerDouble();
        $this->userRepository_double->find(Argument::any())->willReturn($this->getUser());
        $this->lotteryDataService_double->getNextDateDrawByLottery('EuroMillions', new \DateTime('2015-09-16 00:00:00'))->willReturn(new \DateTime());
        $this->betRepository_double->getBetsByDrawDate(new \DateTime())->willReturn(null);
        $this->callValidationApi(true);
        $this->lotteryValidation_double->getXmlResponse()->willReturn(new \SimpleXMLElement(self::$content_with_ok_result));
        $this->logValidationApi_double->add(Argument::type($this->getEntitiesToArgument('LogValidationApi')))->shouldBeCalled();
        $entityManager_stub->flush(Argument::type($this->getEntitiesToArgument('LogValidationApi')))->shouldNotBeCalled();
        $this->betRepository_double->add(Argument::any())->willReturn(true);
        $this->userRepository_double->add(Argument::any())->willReturn(true);
        $entityManager_stub->flush()->shouldNotBeCalled();
        $sut = $this->getSut();
        $actual = $sut->bet($playConfig,$euroMillionsDraw, new \DateTime('2015-09-16 00:00:00'), $this->lotteryValidation_double->reveal());
        $this->assertEquals($expected,$actual);
    }


    /**
     * method bet
     * when called
     * should returnExceptionNoWasPossibleSubstractAmountInUser
     */
    public function test_bet_called_returnExceptionNoWasPossibleSubstractAmountInUser()
    {
        $expected = new ActionResult(false);
        list($playConfig,$euroMillionsDraw) = $this->getPlayConfigAndEuroMillionsDraw();
        $this->userRepository_double->find(Argument::any())->willReturn($this->getUser());
        $this->lotteryDataService_double->getNextDateDrawByLottery('EuroMillions', new \DateTime('2015-09-16 00:00:00'))->willReturn(new \DateTime());
        $this->betRepository_double->getBetsByDrawDate(new \DateTime())->willReturn(null);
        $this->callValidationApi(true);
        $this->betRepository_double->add(Argument::any())->willReturn(true);
        $this->userRepository_double->add($this->getUser())->willThrow(new \Exception());
        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush()->shouldNotBeCalled();
        $sut = $this->getSut();
        $actual = $sut->bet($playConfig,$euroMillionsDraw, new \DateTime('2015-09-16 00:00:00'), $this->lotteryValidation_double->reveal());
        $this->assertEquals($expected,$actual);
    }

    /**
     * method bet
     * when calledWhenABetForNextDrawYetExists
     * should returnServiceActionResultTrueButNotCreateNewBet
     */
    public function test_bet_calledWhenABetForNextDrawYetExists_returnServiceActionResultTrueButNotCreateNewBet()
    {

        $expected = new ActionResult(true);
        $today = new \DateTime('2015-09-16 00:00:00');
        list($playConfig,$euroMillionsDraw) = $this->getPlayConfigAndEuroMillionsDraw();
        $bet = new Bet($playConfig,$euroMillionsDraw);
        $this->userRepository_double->find(Argument::any())->willReturn($this->getUser());
        $this->lotteryDataService_double->getNextDateDrawByLottery('EuroMillions',$today)->willReturn(new \DateTime('2015-09-18 00:00:00'));
        $this->betRepository_double->getBetsByDrawDate(Argument::any())->willReturn($bet);
        $this->betRepository_double->add(Argument::any())->shouldNotBeCalled();
        $sut = $this->getSut();
        $actual = $sut->bet($playConfig,$euroMillionsDraw, $today);
        $this->assertEquals($expected,$actual);

    }

    /**
     * method bet
     * when calledWhenUserWithoutBalance
     * should throwInvalidBalanceException
     */
    public function test_bet_calledWhenUserWithoutBalance_throwInvalidBalanceException()
    {
        $user = $this->getUser();
        list($playConfig,$euroMillionsDraw) = $this->getPlayConfigAndEuroMillionsDraw();
        $this->userRepository_double->find($user->getId()->id())->willReturn($user);
        $user->setBalance(new Money(0, new Currency('EUR')));
        $this->setExpectedException('EuroMillions\web\exceptions\InvalidBalanceException');
        $sut = $this->getSut();
        $sut->bet($playConfig,$euroMillionsDraw,new \DateTime());
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
     * method bet
     * when calledToValidationApi
     * should returnActionResultTrue
     */
    public function test_bet_calledToValidationApi_returnActionResultTrue()
    {
        $expected = new ActionResult(true);
        list($playConfig, $euroMillionsDraw) = $this->exerciseValidationBet($expected);
        $entityManager_stub = $this->getEntityManagerDouble();
        $this->userRepository_double->add(Argument::any())->willReturn(true);
        $this->lotteryValidation_double->getXmlResponse()->willReturn(new \SimpleXMLElement(self::$content_with_ok_result));
        $this->logValidationApi_double->add(Argument::type($this->getEntitiesToArgument('LogValidationApi')))->shouldBeCalled();
        $entityManager_stub->flush(Argument::type($this->getEntitiesToArgument('LogValidationApi')))->shouldNotBeCalled();
        $this->logValidationApi_double->add(Argument::any())->shouldBeCalled();
        $entityManager_stub->flush()->shouldBeCalled();
        $sut = $this->getSut();
        $actual = $sut->bet($playConfig,$euroMillionsDraw, new \DateTime('2015-09-16 00:00:00'), $this->lotteryValidation_double->reveal());
        $this->assertEquals($expected,$actual);
    }


    /**
     * method bet
     * when calledToValidationApi
     * should returnActionResultFalse
     */
    public function test_bet_calledToValidationApi_returnActionResultFalse()
    {
        $expected = new ActionResult(false);
        list($playConfig, $euroMillionsDraw) = $this->exerciseValidationBet($expected);
        $sut = $this->getSut();
        $actual = $sut->bet($playConfig,$euroMillionsDraw, new \DateTime('2015-09-16 00:00:00'), $this->lotteryValidation_double->reveal());
        $this->assertEquals($expected,$actual);

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


    private function exerciseTemporarilyStorePlay($expected)
    {
        $user = $this->getUser();
        $playForm = $this->getPlayFormToStorage();
        $this->playStorageStrategy_double->saveAll($playForm,$user->getId())->willReturn($expected);
        $sut = $this->getSut();
        return $actual = $sut->temporarilyStorePlay($playForm,$user->getId());

    }

    private function exerciseRemoveTemporarilyStorePlay($expected)
    {
        $user = $this->getUser();
        $form = $this->getPlayFormToStorage();
        $this->playStorageStrategy_double->findByKey($user->getId()->id())->willReturn($form->toJson());
        $playConfig = new PlayConfig();
        $playConfig->formToEntity($user,$form->toJson());
        $this->logValidationApi_double->add(Argument::any())->shouldNotBeCalled();
        $this->betRepository_double->add(Argument::any())->shouldNotBeCalled();
        $this->getEntityManagerDouble()->flush($playConfig)->shouldNotBeCalled();
        $this->playStorageStrategy_double->delete($user->getId()->id())->willReturn($expected);
        $sut = $this->getSut();
        return $actual = $sut->play($user);
    }

    private function getSut(){
        $sut = $this->getDomainServiceFactory()->getPlayService($this->lotteryDataService_double->reveal(),
                                                                $this->playStorageStrategy_double->reveal()
                                                                );
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
                'balance' => new Money(50000,new Currency($currency)),
                'validation_token' => '33e4e6a08f82abb38566fc3bb8e8ef0d'
            ]
        );
        return $user;
    }

    private function getPlayFormToStorage()
    {
        $frequency = 1;
        $startDrawDate = '2015-09-18';
        $lastDrawDate = new LastDrawDate($startDrawDate,$frequency);

        $playFormToStorage = new PlayFormToStorage();
        $playFormToStorage->startDrawDate = $startDrawDate;
        $playFormToStorage->frequency = $startDrawDate;
        $playFormToStorage->lastDrawDate = $lastDrawDate->getLastDrawDate();
        $playFormToStorage->drawDays = 2;
        $playFormToStorage->euroMillionsLines = $this->getEuroMillionsLines();

        return $playFormToStorage;
    }

    /**
     * @return array
     */
    private function getEuroMillionsLines()
    {
        $regular_numbers = [1, 2, 3, 4, 5];
        $lucky_numbers = [5, 8];

        $r_numbers = $this->getRegularNumbers($regular_numbers);
        $l_numbers = $this->getLuckyNumbers($lucky_numbers);

        $euroMillionsLine = [
            new EuroMillionsLine($r_numbers,$l_numbers),
            new EuroMillionsLine($r_numbers,$l_numbers),
            new EuroMillionsLine($r_numbers,$l_numbers)
        ];
        return $euroMillionsLine;
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
                'line' => $euroMillionsLine
            ]
        );
        return [$playConfig,$euroMillionsDraw];
    }

    private function getLotteryConfig()
    {
        $lottery = new Lottery();
        $lottery->initialize([
            'id'        => 1,
            'name'      => 'EuroMillions',
            'active'    => 1,
            'frequency' => 'freq',
            'draw_time' => 'draw',
            'single_bet_price' => 23500,
        ]);

        return $lottery;
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
     * @param $expected
     */
    private function callValidationApi($expected)
    {
        $this->lotteryValidation_double->validateBet(Argument::type($this->getEntitiesToArgument('Bet')),
            Argument::type($this->getInterfacesToArgument('ICypherStrategy')),
            Argument::type($this->getVOToArgument('CastilloCypherKey')),
            Argument::type($this->getVOToArgument('CastilloTicketId')),
            Argument::type('\DateTime'))->willReturn(new ActionResult($expected));
    }
}