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
        $data = [
            [
                'name' => 'EuroMillions',
                'jackpot_api' => 'LoteriasyapuestasDotEs',
                'frequency' => 'w0100100',
                'draw_time' => '20:00:00',
                'result_api' => 'LoteriasyapuestasDotEs',
                'single_bet_price_amount' => '300',
                'single_bet_price_currency_name' => 'EUR',
            ],[
                'name' => 'Christmas ',
                'jackpot_api' => 'LoteriasyapuestasDotEs',
                'frequency' => 'y1222',
                'draw_time' => '20:00:00',
                'result_api' => 'LoteriasyapuestasDotEs',
                'single_bet_price_amount' => '2500',
                'single_bet_price_currency_name' => 'EUR',
            ],[
                'name' => 'PowerBall',
                'jackpot_api' => 'Lottorisq',
                'frequency' => 'w0010010',
                'draw_time' => '20:00:00',
                'result_api' => 'Lottorisq',
                'single_bet_price_amount' => '350',
                'single_bet_price_currency_name' => 'EUR',
            ]
        ];
        $lotteries = $this->table('lotteries');
        $lotteries->insert($data)
            ->save();
    }
}
