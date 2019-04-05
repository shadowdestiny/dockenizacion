<?php
return [
    'lotteries' => [
        [
            'id'   => 1,
            'name' => 'EuroMillions',
            'active' => 1,
            'frequency' => 'w0100100',
            'jackpot_api' => 'LoteriasyapuestasDotEs',
            'draw_time' => '20:00:00'
        ],
        [
            'id'   => 3,
            'name' => 'PowerBall',
            'active' => 1,
            'jackpot_api' => 'Lottorisq',
            'frequency' => 'w0001001',
            'draw_time' => '04:30:00',
            'result_api' => 'Lottorisq',
            'single_bet_price_amount' => '350',
            'single_bet_price_currency_name' => 'EUR',
            'time_zone' => 'America/New_York'
        ],
        [
            'id'   => 4,
            'name' => 'MegaMillions',
            'active' => 1,
            'jackpot_api' => 'MegaMillions',
            'frequency' => 'w0010010',
            'draw_time' => '04:00:00',
            'result_api' => 'MegaMillions',
            'single_bet_price_amount' => '350',
            'single_bet_price_currency_name' => 'EUR',
            'time_zone' => 'America/New_York'
        ],
        [
            'id'   => 2,
            'name' => 'Christmas',
            'active' => 1,
            'frequency' => 'w0010010',
            'jackpot_api' => 'LottorisApi',
            'draw_time' => '09:00:00'
        ],
        [
            'id'   => 5,
            'name' => 'EuroJackpot',
            'active' => 1,
            'frequency' => 'w0000100',
            'jackpot_api' => 'LottorisApi',
            'draw_time' => '20:00:00'
        ],

  ]
];