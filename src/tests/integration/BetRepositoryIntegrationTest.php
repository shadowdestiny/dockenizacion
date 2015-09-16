<?php


namespace tests\integration;


use EuroMillions\components\NullPasswordHasher;
use EuroMillions\entities\Bet;
use EuroMillions\entities\EuroMillionsDraw;
use EuroMillions\entities\PlayConfig;
use EuroMillions\entities\User;
use EuroMillions\repositories\BetRepository;
use EuroMillions\vo\Email;
use EuroMillions\vo\EuroMillionsLine;
use EuroMillions\vo\Password;
use EuroMillions\vo\UserId;
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
            'euromillions_draws',
            'play_configs'
        ];
    }

    public function setUp()
    {
        parent::setup();
        $this->sut = $this->entityManager->getRepository('\EuroMillions\entities\Bet');
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
//        $user = $this->entityManager->find('EuroMillions\entities\User', '9098299B-14AC-4124-8DB0-19571EDABE55');
        $playConfig = $this->entityManager->find('EuroMillions\entities\PlayConfig', 1);
        $euroMillionsDraw = $this->entityManager->find('EuroMillions\entities\EuroMillionsDraw', 1);
        $bet = new Bet($playConfig,$euroMillionsDraw);
        $this->sut->add($bet);
        $this->entityManager->flush();
        $actual = $this->entityManager
            ->createQuery(
                'SELECT b'
                .    ' FROM \EuroMillions\entities\Bet b'
                .    ' WHERE b.play_config = :play_config_id')
            ->setMaxResults(1)
            ->setParameters(['play_config_id' => $playConfig->getId() ])
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