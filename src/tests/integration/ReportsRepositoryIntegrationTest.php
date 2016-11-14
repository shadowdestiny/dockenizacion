<?php


namespace tests\integration;


use EuroMillions\tests\base\DatabaseIntegrationTestBase;
use EuroMillions\web\repositories\ReportsRepository;

class ReportsRepositoryIntegrationTest extends DatabaseIntegrationTestBase
{

    /** @var  ReportsRepository*/
    protected $sut;

    /**
     * Child classes must implement this method. Return empty array if no fixtures are needed
     * @return array
     */
    protected function getFixtures()
    {
        return [
            'lotteries',
            'euromillions_draws',
            'users',
            'play_configs',
            'bets',
            'transactions'

        ];
    }

    public function setUp()
    {
        parent::setup();
        $this->sut = new ReportsRepository($this->entityManager);
    }

    /**
     * method getMonthlySales
     * when called
     * should returnArrayWithReports
     */
    public function test_getMonthlySales_called_returnArrayWithReports()
    {
        $actual = $this->sut->getMonthlySales();
        $this->assertEquals('May',$actual[0]['month']);
    }
}
