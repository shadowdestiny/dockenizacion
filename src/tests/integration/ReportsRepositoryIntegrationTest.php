<?php


namespace tests\integration;


use EuroMillions\tests\base\DatabaseIntegrationTestBase;
use EuroMillions\web\repositories\ReportsRepository;

class ReportsRepositoryIntegrationTest extends DatabaseIntegrationTestBase
{

    /** @var  ReportsRepository */
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
        $expected = [
            'month' => 'October',
            'total_bets' => '2',
            'gross_sales' => '6.00',
            'gross_margin' => '1.00',
            'winnings' => null,
        ];

        $this->assertEquals($expected, $actual[3]);
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
            ]
        ];
        $this->assertEquals($expectedResponse, $this->sut->getSalesDraw());
    }

    /**
     * method getCustomerData
     * when called
     * should returnArrayWithProperData
     */
    public function test_getCustomerData_called_returnArrayWithProperData()
    {
        $this->markTestSkipped('This test don\'t works anymore :( | Fix it? ');

        $expected = [
            'name' => '',
            'surname' => '',
            'email' => 'algarrobo@currojimenez.com',
            'created' => '',
            'id' => '9098299B-14AC-4124-8DB0-19571EDABE55',
            'currency' => null,
            'country' => '',
            'money_deposited' => '',
            'winnings' => null,
            'balance' => 3000.0500,
            'num_bets' => 1
        ];
        $actual = $this->sut->getCustomersData();
        $this->assertEquals($expected,$actual[0]);
    }

    /**
     * method getPastGamesWithPrizes
     * when called
     * should returnArrayWithProperData
     */
    public function test_getPastGamesWithPrizes()
    {
        $actual = $this->sut->getPastGamesWithPrizes('9098299B-14AC-4124-8DB0-19571EDABE55');

        $this->assertEquals('EuroJackpot',$actual[0][0]->getPlayConfig()->getLottery()->getName());
    }
}
