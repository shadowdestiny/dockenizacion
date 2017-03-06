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
            'month' => 'November',
            'total_bets' => '1',
            'gross_sales' => '3.00',
            'gross_margin' => '0.50',
            'winnings' => '0',
        ];

        $this->assertEquals($expected, $actual[2]);
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
}
