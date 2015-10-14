<?php


namespace tests\unit;


use EuroMillions\components\NullPasswordHasher;
use EuroMillions\config\Namespaces;
use EuroMillions\entities\Bet;
use EuroMillions\entities\EuroMillionsDraw;
use EuroMillions\entities\Lottery;
use EuroMillions\entities\PlayConfig;
use EuroMillions\entities\User;
use EuroMillions\vo\Email;
use EuroMillions\vo\EuroMillionsLine;
use EuroMillions\vo\LastDrawDate;
use EuroMillions\vo\Password;
use EuroMillions\vo\PlayForm;
use EuroMillions\vo\PlayFormToStorage;
use EuroMillions\vo\ServiceActionResult;
use EuroMillions\vo\UserId;
use Money\Currency;
use Money\Money;
use Prophecy\Argument;
use tests\base\EuroMillionsResultRelatedTest;
use tests\base\UnitTestBase;

class PlayServiceUnitTest extends UnitTestBase
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
        $this->playConfigRepository_double = $this->getRepositoryDouble('PlayConfigRepository');
        $this->lotteryDataService_double = $this->getServiceDouble('LotteriesDataService');
        $this->betRepository_double = $this->getRepositoryDouble('BetRepository');
        $this->lotteryDrawRepository_double = $this->getRepositoryDouble('EuroMillions\entities\Lottery');
        $this->playStorageStrategy_double = $this->getInterfaceDouble('IPlayStorageStrategy');
        $this->userRepository_double = $this->getRepositoryDouble('UserRepository');
        $this->authService_double = $this->getServiceDouble('AuthService');
        parent::setUp();
    }

    /**
     * method play
     * when called
     * should returnServiceActionResultTrue
     */
    public function test_play_called_returnServiceActionResultTrue()
    {
        $expected = new ServiceActionResult(true);
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
        $expected = new ServiceActionResult(false);
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
        $expected = new ServiceActionResult(true);
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
        $expected = new ServiceActionResult(false);
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
        $expected = new ServiceActionResult(false,'The search key doesn\'t exist');
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
        $expected = new ServiceActionResult(true);
        list($playConfig,$euroMillionsDraw) = $this->getPlayConfigAndEuroMillionsDraw();
        $this->userRepository_double->find(Argument::any())->willReturn($this->getUser());
        $this->betRepository_double->getBetsByDrawDate(Argument::any())->willReturn(null);
        $this->betRepository_double->add(Argument::any())->willReturn(true);
        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush(Argument::any())->shouldNotBeCalled();
        $sut = $this->getSut();
        $actual = $sut->bet($playConfig,$euroMillionsDraw, new \DateTime('2015-09-16 00:00:00'));
        $this->assertEquals($expected,$actual);
    }

    /**
     * method bet
     * when calledWhenABetForNextDrawYetExists
     * should returnServiceActionResultTrueButNotCreateNewBet
     */
    public function test_bet_calledWhenABetForNextDrawYetExists_returnServiceActionResultTrueButNotCreateNewBet()
    {

        $expected = new ServiceActionResult(true);
        $today = new \DateTime('2015-09-16 00:00:00');
        list($playConfig,$euroMillionsDraw) = $this->getPlayConfigAndEuroMillionsDraw();
        $bet = new Bet($playConfig,$euroMillionsDraw);
        $this->userRepository_double->find(Argument::any())->willReturn($this->getUser());
        $this->lotteryDataService_double->getNextDateDrawByLottery('EuroMillions',$today)->willReturn('2015-09-18 00:00:00');
        $this->betRepository_double->getBetsByDrawDate(Argument::any())->willReturn($bet);
        $this->betRepository_double->add(Argument::any())->shouldNotBeCalled();
        $sut = $this->getSut();
        $actual = $sut->bet($playConfig,$euroMillionsDraw, $today);
        $this->assertEquals($expected,$actual);

    }

    /**
     * method bet
     * when calledWhenUserWithoutBalance
     * should throwExceptionNoBalance
     */
    public function test_bet_calledWhenUserWithoutBalance_throwExceptionNoBalance()
    {
        $user = $this->getUser();
        list($playConfig,$euroMillionsDraw) = $this->getPlayConfigAndEuroMillionsDraw();
        $this->userRepository_double->find($user->getId()->id())->willReturn($user);
        $user->setBalance(new Money(0, new Currency('EUR')));
        $this->setExpectedException('EuroMillions\exceptions\InvalidBalanceException');
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
        $expected = new ServiceActionResult(true);
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
        $expected = new ServiceActionResult(false);
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
        $date = '2015-10-05';
        $sut = $this->getSut();
        $this->playConfigRepository_double->getPlayConfigsByDrawDayAndDate($date)->willReturn(true);
        $actual = $sut->getPlaysConfigToBet($date);
        $this->assertGreaterThanOrEqual($expected,count($actual->getValues()));
    }


    private function exerciseTemporarilyStorePlay($expected)
    {
        $playForm = $this->getPlayFormToStorage();
        $this->playStorageStrategy_double->saveAll($playForm)->willReturn($expected);
        $sut = $this->getSut();
        return $actual = $sut->temporarilyStorePlay($playForm);

    }

    private function exerciseRemoveTemporarilyStorePlay($expected)
    {
        $user = $this->getUser();
        $form = $this->getPlayFormToStorage();
        $this->playStorageStrategy_double->findByKey($user->getId()->id())->willReturn($form->toJson());
        $playConfig = new PlayConfig();
        $playConfig->formToEntity($user,$form->toJson());
        $this->betRepository_double->add(Argument::any())->shouldNotBeCalled();
        $this->getEntityManagerDouble()->flush($playConfig)->shouldNotBeCalled();
        $this->playStorageStrategy_double->delete($user->getId()->id())->willReturn($expected);
        $sut = $this->getSut();
        return $actual = $sut->play($user);
    }

    private function getSut(){
        $sut = $this->getDomainServiceFactory()->getPlayService($this->lotteryDataService_double->reveal(), $this->playStorageStrategy_double->reveal());
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
            'single_bet_price' => 235,
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


}