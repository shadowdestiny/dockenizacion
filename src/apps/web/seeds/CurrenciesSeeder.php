<?php

use Phinx\Seed\AbstractSeed;

class CurrenciesSeeder extends AbstractSeed
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
        $currencies = $this->table('currencies');
        $currencies->insert($this->getData())
            ->save();
    }

    private function getData()
    {
        return [
            [
                'code' => 'EUR',
                'name' => 'Euro',
                'order' => 1
            ],
            [
                'code' => 'USD',
                'name' => 'Us Dollar',
                'order' => 2
            ],
            [
                'code' => 'COP',
                'name' => 'Colombian Peso',
                'order' => 7
            ],
            [
                'code' => 'GBP',
                'name' => 'Pound Sterling',
                'order' => 3
            ],
            [
                'code' => 'RUB',
                'name' => 'Russian Ruble',
                'order' => 5
            ],
            [
                'code' => 'CHF',
                'name' => 'Swiss Franc',
                'order' => 4
            ],
            [
                'code' => 'AUD',
                'name' => 'Australian Dolar',
                'order' => 6
            ],
            [
                'code' => 'RON',
                'name' => 'Romanian Leu',
                'order' => 8
            ],
            [
                'code' => 'BGN',
                'name' => 'Bulgarian Lev',
                'order' => 9
            ],
            [
                'code' => 'ZAR',
                'name' => 'South African Rand',
                'order' => 10
            ],
            [
                'code' => 'SEK',
                'name' => 'Swedish Krone',
                'order' => 11
            ],
            [
                'code' => 'DKK',
                'name' => 'Danish Krone',
                'order' => 12
            ],
            [
                'code' => 'INR',
                'name' => 'Indian Rupee',
                'order' => 13
            ],
            [
                'code' => 'BYR',
                'name' => 'Belarusian Ruble',
                'order' => 14
            ],
            [
                'code' => 'CAD',
                'name' => 'Canadian Dollar',
                'order' => 15
            ],
            [
                'code' => 'CNY',
                'name' => 'Chinese Yuan',
                'order' => 16
            ],
            [
                'code' => 'JPY',
                'name' => 'Japanese Yen',
                'order' => 17
            ],
            [
                'code' => 'THB',
                'name' => 'Thai Baht',
                'order' => 18
            ],
            [
                'code' => 'UAH',
                'name' => 'Ukranian Hryvnia',
                'order' => 19
            ],
            [
                'code' => 'HUF',
                'name' => 'Hungarian Forint',
                'order' => 20
            ],
            [
                'code' => 'CZK',
                'name' => 'Czech koruna',
                'order' => 21
            ],
            [
                'code' => 'PLN',
                'name' => 'Polish Zloty',
                'order' => 22
            ],
            [
                'code' => 'LBP',
                'name' => 'Lebanese Pound',
                'order' => 23
            ],
            [
                'code' => 'NOK',
                'name' => 'Norwegian Krone',
                'order' => 24
            ],
            [
                'code' => 'MDL',
                'name' => 'Moldovan Leu',
                'order' => 25
            ],
            [
                'code' => 'MXN',
                'name' => 'Mexican Peso',
                'order' => 26
            ],
            [
                'code' => 'NZD',
                'name' => 'New Zealand Dollar',
                'order' => 27
            ],
            [
                'code' => 'TRY',
                'name' => 'Turkish Lira',
                'order' => 28
            ],
            [
                'code' => 'BRL',
                'name' => 'Brazilian Real',
                'order' => 29
            ],
            [
                'code' => 'NGN',
                'name' => 'Nigerian Naira',
                'order' => 30
            ],
            [
                'code' => 'AZN',
                'name' => 'Azerbaijani Manat',
                'order' => 31
            ],
            [
                'code' => 'PHP',
                'name' => 'Phillippine Peso',
                'order' => 32
            ],
            [
                'code' => 'KZT',
                'name' => 'Kazakhstani Tenge',
                'order' => 33
            ],
            [
                'code' => 'ALL',
                'name' => 'Albanian Lek',
                'order' => 34
            ],
            [
                'code' => 'RSD',
                'name' => 'Serbian Dinar',
                'order' => 35
            ],
            [
                'code' => 'MKD',
                'name' => 'Macedonian Denar',
                'order' => 36
            ],
            [
                'code' => 'KES',
                'name' => 'Kenyan Shilling',
                'order' => 37
            ],
            [
                'code' => 'IDR',
                'name' => 'Indonesian Rupiah',
                'order' => 38
            ],
            [
                'code' => 'ILS',
                'name' => 'Israeli Shekel',
                'order' => 39
            ],
            [
                'code' => 'CLP',
                'name' => 'Chilean Peso',
                'order' => 40
            ],
            [
                'code' => 'KRW',
                'name' => 'South Korean Won',
                'order' => 41
            ],
            [
                'code' => 'SGD',
                'name' => 'Singapore Dollar',
                'order' => 42
            ],
            [
                'code' => 'PKR',
                'name' => 'Pakistani Rupee',
                'order' => 43
            ],
            [
                'code' => 'BAM',
                'name' => 'Bosnia-Herzegovina Mark',
                'order' => 44
            ],
            [
                'code' => 'HKD',
                'name' => 'Hong Kong Dollar',
                'order' => 45
            ],
            [
                'code' => 'GEL',
                'name' => 'Georgian Lari',
                'order' => 46
            ],
            [
                'code' => 'QAR',
                'name' => 'Qatari Riyal',
                'order' => 47
            ],
            [
                'code' => 'MYR',
                'name' => 'Malaysian Ringgit',
                'order' => 48
            ],
            [
                'code' => 'ARS',
                'name' => 'Argentine Peso',
                'order' => 49
            ],
            [
                'code' => 'PEN',
                'name' => 'Peruvian Sol',
                'order' => 50
            ],
            [
                'code' => 'ISK',
                'name' => 'Icelandic Krona',
                'order' => 51
            ],
            [
                'code' => 'BOB',
                'name' => 'Bolivian Boliviano',
                'order' => 52
            ],
            [
                'code' => 'PYG',
                'name' => 'Paraguay Guarani',
                'order' => 53
            ],
            [
                'code' => 'VEF',
                'name' => 'Venezuelan Bolívar',
                'order' => 54
            ],
            [
                'code' => 'AED',
                'name' => 'Emirati Dirham',
                'order' => 55
            ],
        ];
    }


}
