<?php

use Phinx\Seed\AbstractSeed;

class LotterySeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');
        $this->execute('TRUNCATE TABLE lotteries');

        $data = [
            [
                'name' => 'EuroMillions',
                'jackpot_api' => 'LoteriasyapuestasDotEs',
                'frequency' => 'w0100100',
                'draw_time' => '20:00:00',
                'result_api' => 'LoteriasyapuestasDotEs',
                'single_bet_price_amount' => '300',
                'single_bet_price_currency_name' => 'EUR',
                'time_zone' => 'Europe/Madrid'
            ],[
                'name' => 'Christmas ',
                'jackpot_api' => 'LoteriasyapuestasDotEs',
                'frequency' => 'y1222',
                'draw_time' => '20:00:00',
                'result_api' => 'LoteriasyapuestasDotEs',
                'single_bet_price_amount' => '2500',
                'single_bet_price_currency_name' => 'EUR',
                'time_zone' => 'Europe/Madrid'
            ],[
                'name' => 'PowerBall',
                'jackpot_api' => 'Lottorisq',
                'frequency' => 'w0001001',
                'draw_time' => '04:30:00',
                'result_api' => 'Lottorisq',
                'single_bet_price_amount' => '350',
                'single_bet_price_currency_name' => 'EUR',
                'time_zone' => 'America/New_York'
            ],
            [
                'name' => 'MegaMillions',
                'jackpot_api' => 'MegaMillions',
                'frequency' => 'w0010010',
                'draw_time' => '04:00:00',
                'result_api' => 'MegaMillions',
                'single_bet_price_amount' => '350',
                'single_bet_price_currency_name' => 'EUR',
                'time_zone' => 'America/New_York'
            ],
            [
                'name' => 'EuroJackpot',
                'jackpot_api' => 'EuroJackpot',
                'frequency' => 'w0000100',
                'draw_time' => '20:00:00',
                'result_api' => 'EuroJackpot',
                'single_bet_price_amount' => '200',
                'single_bet_price_currency_name' => 'EUR',
                'time_zone' => 'Europe/Madrid'
            ]
        ];

        $lotteries = $this->table('lotteries');
        $lotteries->insert($data)
            ->save();
        $this->execute('SET FOREIGN_KEY_CHECKS = 1');
    }
}
