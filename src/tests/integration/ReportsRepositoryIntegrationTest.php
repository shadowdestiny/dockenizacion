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
            'transactions',
            'log_validation_api',
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

    /**
     * method getSalesDraw
     * when called
     * should returnArrayWithReports
     */
    public function test_getSalesDraw_called_returnArrayWithReports()
    {
        $expectedResponse = [
            [
                'em' => 'EM',
                'id' => '1',
                'draw_date' => '2015-05-12',
                'draw_status' => 'Finished',
                'count_id' => '1',
                'count_id_3' => '3.00',
                'count_id_05' => '0.50',
            ]
        ];
        $this->assertEquals($expectedResponse, $this->sut->getSalesDraw());
    }
}
