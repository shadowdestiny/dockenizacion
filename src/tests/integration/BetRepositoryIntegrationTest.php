<?php


namespace EuroMillions\tests\integration;


use EuroMillions\web\entities\Bet;
use EuroMillions\web\repositories\BetRepository;
use EuroMillions\tests\base\DatabaseIntegrationTestBase;
use EuroMillions\tests\base\EuroMillionsResultRelatedTest;

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
}