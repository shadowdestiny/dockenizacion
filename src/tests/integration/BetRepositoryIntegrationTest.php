<?php


namespace tests\integration;


use EuroMillions\web\components\NullPasswordHasher;
use EuroMillions\web\entities\Bet;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\repositories\BetRepository;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\EuroMillionsLine;
use EuroMillions\web\vo\Password;
use EuroMillions\web\vo\UserId;
use Money\Currency;
use Money\Money;
use tests\base\DatabaseIntegrationTestBase;
use tests\base\EuroMillionsResultRelatedTest;

class BetRepositoryIntegrationTest extends DatabaseIntegrationTestBase
{
    use EuroMillionsResultRelatedTest;

    /** @var  BetRepository */
    protected $sut;

    /**
     * Child classes must implement this method. Return empty array if no fixtures are needed
     * @return array
     */
    protected function getFixtures()
    {
        return [
            'users',
            'lotteries',
            'play_configs',
            'euromillions_draws',
            'bets'
        ];
    }

    public function setUp()
    {
        parent::setup();
        $this->sut = $this->entityManager->getRepository($this->getEntitiesToArgument('Bet'));
    }

    /**
     * method add
     * when calledWithPlayConfigInstanceAndEuroMillionsDraw
     * should storeCorrectlyInTheDatabse
     */
    public function test_add_calledWithPlayConfigInstanceAndEuroMillionsDraw_storeCorrectlyInTheDatabse()
    {
        list($bet, $actual) = $this->exerciseAdd();
        $this->assertEquals($bet,$actual);
    }

    private function exerciseAdd()
    {
        $euroMillionsDraw = $this->entityManager->find($this->getEntitiesToArgument('EuroMillionsDraw'), 2);
        $playConfig = $this->entityManager->find($this->getEntitiesToArgument('PlayConfig'), 1);
        $bet = new Bet($playConfig,$euroMillionsDraw);
        $this->sut->add($bet);
        $this->entityManager->flush($bet);
        $actual = $this->entityManager
            ->createQuery(
                'SELECT b'
                .    ' FROM \EuroMillions\web\entities\Bet b'
                .    ' WHERE b.euromillionsDraw = :euromillions_draw_id')
            ->setParameters(['euromillions_draw_id' => $euroMillionsDraw->getId() ])
            ->getResult()[0];
        return array($bet, $actual);
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
}