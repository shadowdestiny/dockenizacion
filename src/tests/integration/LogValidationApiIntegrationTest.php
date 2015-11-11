<?php


namespace tests\integration;


use EuroMillions\web\entities\Bet;
use EuroMillions\web\entities\LogValidationApi;
use tests\base\DatabaseIntegrationTestBase;

class LogValidationApiIntegrationTest extends DatabaseIntegrationTestBase
{

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
        $this->sut = $this->entityManager->getRepository('\EuroMillions\web\entities\LogValidationApi');
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

}