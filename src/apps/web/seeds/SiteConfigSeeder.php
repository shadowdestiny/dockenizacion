<?php

use Phinx\Seed\AbstractSeed;

class SiteConfigSeeder extends AbstractSeed
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
                'id' => '1',
                'fee_amount' => '35',
                'fee_currency_name' => 'EUR',
                'fee_to_limit_amount' => '1200',
                'fee_to_limit_currency_name' => 'EUR',
                'default_currency_name' => 'EUR'
            ]
        ];

        $siteConfig = $this->table('site_config');
        $siteConfig->insert($data)
            ->save();
    }
}
