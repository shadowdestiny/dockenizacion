<?php


namespace tests\unit;


use EuroMillions\components\NullPasswordHasher;
use EuroMillions\config\Namespaces;
use EuroMillions\entities\Bet;
use EuroMillions\entities\EuroMillionsDraw;
use EuroMillions\entities\PlayConfig;
use EuroMillions\entities\User;
use EuroMillions\vo\Email;
use EuroMillions\vo\EuroMillionsLine;
use EuroMillions\vo\Password;
use EuroMillions\vo\PlayForm;
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
        $this->userRepository_double = $this->getRepositoryDouble('EuroMillions\entities\User');
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
        $user = $this->getUser();
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
     * method bet
     * when calledWhenTheresNoBetInDB
     * should returnServiceActionResultTrueAndCreateNewBet
     */
    public function test_bet_calledWhenTheresNoBetInDB_returnServiceActionResultTrueAndCreateNewBet()
    {
        $expected = new ServiceActionResult(true);
        list($playConfig,$euroMillionsDraw) = $this->getPlayConfigAndEuroMillionsDraw();
        $this->betRepository_double->getBetsByDrawDate(Argument::any())->willReturn(null);
        $this->betRepository_double->add(Argument::any())->willReturn(true);
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
        $this->lotteryDataService_double->getNextDrawByLottery('EuroMillions',$today)->willReturn('2015-09-18 00:00:00');
        $this->betRepository_double->getBetsByDrawDate(Argument::any())->willReturn($bet);
        $this->betRepository_double->add(Argument::any())->shouldNotBeCalled();
        $sut = $this->getSut();
        $actual = $sut->bet($playConfig,$euroMillionsDraw, $today);
        $this->assertEquals($expected,$actual);

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


    private function exerciseTemporarilyStorePlay($expected)
    {
        $euroMillionsLine = $this->getEuroMillionsLines();
        $playForm = new PlayForm($euroMillionsLine);
        $this->playStorageStrategy_double->saveAll($playForm->getEuroMillionsLines())->willReturn($expected);
        $sut = $this->getSut();
        return $actual = $sut->temporarilyStorePlay($playForm);

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
        $playConfig = new PlayConfig();
        $playConfig->initialize([
                'user' => $user,
                'line' => $euroMillionsLine
            ]
        );
        return [$playConfig,$euroMillionsDraw];
    }


}