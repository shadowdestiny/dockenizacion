<?php


namespace EuroMillions\tests\integration;


use EuroMillions\web\entities\Bet;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\LogValidationApi;
use EuroMillions\tests\base\DatabaseIntegrationTestBase;
use EuroMillions\web\repositories\LogValidationApiRepository;

class LogValidationApiIntegrationTest extends DatabaseIntegrationTestBase
{

    /** @var  LogValidationApiRepository */
    private $sut;

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
            'log_validation_api'
        ];
    }

    public function setUp()
    {
        parent::setup();
        $this->sut = $this->entityManager->getRepository($this->getEntitiesToArgument('LogValidationApi'));
    }

    /**
     * method add
     * when called
     * should storeCorrectlyeInDatabase
     */
    public function test_add_called_storeCorrectlyeInDatabase()
    {

        /** @var Bet $bet */
        $bet = $this->entityManager->find('EuroMillions\web\entities\Bet', 2);
        $log_api_reponse = new LogValidationApi();
        $log_api_reponse->initialize([
            'id' => 1,
            'id_provider' => 1,
            'id_ticket' => $bet->getCastilloBet()->id(),
            'status' => 'OK',
            'response' => 'XML',
            'received' => new \DateTime('2015-11-11 09:46:37')
        ]);

        $this->sut->add($log_api_reponse);
        $this->entityManager->flush($log_api_reponse);
        $actual = $this->entityManager
            ->createQuery(
                'SELECT l'
                .    ' FROM \EuroMillions\web\entities\LogValidationApi l'
                .    ' WHERE l.id_ticket = :castillo_bet_id')
            ->setParameters(['castillo_bet_id' => $bet->getCastilloBet()->id() ])
            ->getResult()[0];

        $this->assertEquals($log_api_reponse,$actual);
    }


    /**
     * method persistValidationAndBetsFromPlayConfigsCollection
     * when called
     * should storeCorrectlyInDatabase
     */
    public function test_persistValidationAndBetsFromPlayConfigsCollection_called_storeCorrectlyInDatabase()
    {
        $playConfigOne = $this->entityManager->find('EuroMillions\web\entities\PlayConfig', 1);
        $playConfigTwo = $this->entityManager->find('EuroMillions\web\entities\PlayConfig', 2);
        $playConfigCollection = [$playConfigOne,$playConfigTwo];
        /** @var EuroMillionsDraw $draw */
        $draw = $this->entityManager->find('EuroMillions\web\entities\EuroMillionsDraw', 1);
        $id_ticket = '123456';
        $this->sut->persistValidationsAndBetsFromPlayConfigsCollection($playConfigCollection,$draw,$id_ticket);

        $actual = $this->entityManager
            ->createQuery(
                'SELECT l'
                .    ' FROM \EuroMillions\web\entities\LogValidationApi l')
            ->getResult();

        $actualBet = $this->entityManager
            ->createQuery(
                'SELECT b'
                .    ' FROM \EuroMillions\web\entities\Bet b')
            ->getResult();

        $this->assertEquals(4,count($actual));
        $this->assertEquals(7,count($actualBet));
    }

}