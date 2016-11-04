<?php


namespace EuroMillions\tests\integration;


use EuroMillions\tests\helpers\mothers\RaffleMother;
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
            'bets',
            'matcher',
            'log_validation_api'
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

    /**
     * method getBetsPlayedLastDraw
     * when calledWithADateLastDraw
     * should returnProperResult
     */
    public function test_getBetsPlayedLastDraw_calledWithADateLastDraw_returnProperResult()
    {
        $actual = $this->sut->getBetsPlayedLastDraw(new \DateTime('2015-10-02'));
        $this->assertEquals(3, $actual[0]->getId());
        $this->assertEquals(1, count($actual));
    }

    /**
     * method getBetsPlayedLastDraw
     * when calledWithADateLastDraw
     * should returnProperResult
     */
    public function test_getRafflePlayedLastDraw_calledWithADateLastDraw_returnProperResult()
    {
        $actual = $this->sut->getRafflePlayedLastDraw(new \DateTime('2016-09-30'));
        $raffle = RaffleMother::anRaffle()->getValue();
        $this->assertEquals(substr($actual[0]['raffle'], 0 , 8), $raffle);
    }

    /**
     * method getMatchNumbers
     * when called
     * should returnResultWithMatchNumbers
     */
    public function test_getMatchNumbers_called_returnResultWithMatchNumbers()
    {
        $actual = $this->sut->getMatchNumbers(new \DateTime('2015-05-12'), '9098299B-14AC-4124-8DB0-19571EDABE55');
        $expected = ['numbers' => '11,20,22,29,0',
                     'stars' => '1,0'
                    ];
        $this->assertEquals($expected,$actual);

    }

    /**
     * method obtainWinnerBetById
     * when calledPassingABetIdValid
     * should returnResultWithMatchNumbers
     */
    public function test_obtainWinnerBetById_calledPassingABetIdValid_returnResultWithMatchNumbers()
    {
        $betId = 3;
        $actual = $this->sut->obtainWinnerBetById($betId);
        $this->assertEquals(1, count($actual));
        $this->assertEquals('11,12',$actual[0][0]->getMatchNumbers());
    }

    /**
     * method getPastGamesWithPrizes
     * when called
     * should returnPastGamesWithPrizes
     */
    public function test_getPastGamesWithPrizes_called_returnPastGamesWithPrizes()
    {
        $userId = '9098299B-14AC-4124-8DB0-19571EDABE56';
        $actual = $this->sut->getPastGamesWithPrizes($userId);
        $this->assertEquals(2,count($actual));
        $this->assertEquals('11,12',$actual[0][0]->getMatchNumbers());
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