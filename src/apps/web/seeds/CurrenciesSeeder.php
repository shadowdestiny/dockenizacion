<?php

use EuroMillions\web\components\EmTranslationAdapter;
use EuroMillions\web\services\preferences_strategies\WebLanguageStrategy;
use Phalcon\Di;
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
        $di = Di::getDefault();
        $entityManager = $di->get('entityManager');
        $translationAdapter = new EmTranslationAdapter((new WebLanguageStrategy($di->get('session'), $di->get('request')))->get(), $entityManager->getRepository('EuroMillions\web\entities\TranslationDetail'));

        return [
            [
                'code' => $translationAdapter->query("eur_code"),
                'name' => $translationAdapter->query("eur_name"),
                'order' => 1
            ],
            [
                'code' => $translationAdapter->query("usd_code"),
                'name' => $translationAdapter->query("usd_name"),
                'order' => 2
            ],
            [
                'code' => $translationAdapter->query("cop_code"),
                'name' => $translationAdapter->query("cop_name"),
                'order' => 7
            ],
            [
                'code' => $translationAdapter->query("gbp_code"),
                'name' => $translationAdapter->query("gbp_name"),
                'order' => 3
            ],
            [
                'code' => $translationAdapter->query("rub_code"),
                'name' => $translationAdapter->query("rub_name"),
                'order' => 5
            ],
            [
                'code' => $translationAdapter->query("chf_code"),
                'name' => $translationAdapter->query("chf_name"),
                'order' => 4
            ],
            [
                'code' => $translationAdapter->query("aud_code"),
                'name' => $translationAdapter->query("aud_name"),
                'order' => 6
            ],
            [
                'code' => $translationAdapter->query("ron_code"),
                'name' => $translationAdapter->query("ron_name"),
                'order' => 8
            ],
            [
                'code' => $translationAdapter->query("bgn_code"),
                'name' => $translationAdapter->query("bgn_name"),
                'order' => 9
            ],
            [
                'code' => $translationAdapter->query("zar_code"),
                'name' => $translationAdapter->query("zar_name"),
                'order' => 10
            ],
            [
                'code' => $translationAdapter->query("sek_code"),
                'name' => $translationAdapter->query("sek_name"),
                'order' => 11
            ],
            [
                'code' => $translationAdapter->query("dkk_code"),
                'name' => $translationAdapter->query("dkk_name"),
                'order' => 12
            ],
            [
                'code' => $translationAdapter->query("inr_code"),
                'name' => $translationAdapter->query("inr_name"),
                'order' => 13
            ],
            [
                'code' => $translationAdapter->query("byr_code"),
                'name' => $translationAdapter->query("byr_name"),
                'order' => 14
            ],
            [
                'code' => $translationAdapter->query("cad_code"),
                'name' => $translationAdapter->query("cad_name"),
                'order' => 15
            ],
            [
                'code' => $translationAdapter->query("cny_code"),
                'name' => $translationAdapter->query("cny_name"),
                'order' => 16
            ],
            [
                'code' => $translationAdapter->query("jpy_code"),
                'name' => $translationAdapter->query("jpy_name"),
                'order' => 17
            ],
            [
                'code' => $translationAdapter->query("thb_code"),
                'name' => $translationAdapter->query("thb_name"),
                'order' => 18
            ],
            [
                'code' => $translationAdapter->query("uah_code"),
                'name' => $translationAdapter->query("uah_name"),
                'order' => 19
            ],
            [
                'code' => $translationAdapter->query("huf_code"),
                'name' => $translationAdapter->query("huf_name"),
                'order' => 20
            ],
            [
                'code' => $translationAdapter->query("czk_code"),
                'name' => $translationAdapter->query("czk_name"),
                'order' => 21
            ],
            [
                'code' => $translationAdapter->query("pln_code"),
                'name' => $translationAdapter->query("pln_name"),
                'order' => 22
            ],
            [
                'code' => $translationAdapter->query("lbp_code"),
                'name' => $translationAdapter->query("lbp_name"),
                'order' => 23
            ],
            [
                'code' => $translationAdapter->query("nok_code"),
                'name' => $translationAdapter->query("nok_name"),
                'order' => 24
            ],
            [
                'code' => $translationAdapter->query("mdl_code"),
                'name' => $translationAdapter->query("mdl_name"),
                'order' => 25
            ],
            [
                'code' => $translationAdapter->query("mxn_code"),
                'name' => $translationAdapter->query("mxn_name"),
                'order' => 26
            ],
            [
                'code' => $translationAdapter->query("nzd_code"),
                'name' => $translationAdapter->query("nzd_name"),
                'order' => 27
            ],
            [
                'code' => $translationAdapter->query("try_code"),
                'name' => $translationAdapter->query("try_name"),
                'order' => 28
            ],
            [
                'code' => $translationAdapter->query("brl_code"),
                'name' => $translationAdapter->query("brl_name"),
                'order' => 29
            ],
            [
                'code' => $translationAdapter->query("ngn_code"),
                'name' => $translationAdapter->query("ngn_name"),
                'order' => 30
            ],
            [
                'code' => $translationAdapter->query("azn_code"),
                'name' => $translationAdapter->query("azn_name"),
                'order' => 31
            ],
            [
                'code' => $translationAdapter->query("php_code"),
                'name' => $translationAdapter->query("php_name"),
                'order' => 32
            ],
            [
                'code' => $translationAdapter->query("kzt_code"),
                'name' => $translationAdapter->query("kzt_name"),
                'order' => 33
            ],
            [
                'code' => $translationAdapter->query("all_code"),
                'name' => $translationAdapter->query("all_name"),
                'order' => 34
            ],
            [
                'code' => $translationAdapter->query("rsd_code"),
                'name' => $translationAdapter->query("rsd_name"),
                'order' => 35
            ],
            [
                'code' => $translationAdapter->query("mkd_code"),
                'name' => $translationAdapter->query("mkd_name"),
                'order' => 36
            ],
            [
                'code' => $translationAdapter->query("kes_code"),
                'name' => $translationAdapter->query("kes_name"),
                'order' => 37
            ],
            [
                'code' => $translationAdapter->query("idr_code"),
                'name' => $translationAdapter->query("idr_name"),
                'order' => 38
            ],
            [
                'code' => $translationAdapter->query("ils_code"),
                'name' => $translationAdapter->query("ils_name"),
                'order' => 39
            ],
            [
                'code' => $translationAdapter->query("clp_code"),
                'name' => $translationAdapter->query("clp_name"),
                'order' => 40
            ],
            [
                'code' => $translationAdapter->query("krw_code"),
                'name' => $translationAdapter->query("krw_name"),
                'order' => 41
            ],
            [
                'code' => $translationAdapter->query("sgd_code"),
                'name' => $translationAdapter->query("sgd_name"),
                'order' => 42
            ],
            [
                'code' => $translationAdapter->query("pkr_code"),
                'name' => $translationAdapter->query("pkr_name"),
                'order' => 43
            ],
            [
                'code' => $translationAdapter->query("bam_code"),
                'name' => $translationAdapter->query("bam_name"),
                'order' => 44
            ],
            [
                'code' => $translationAdapter->query("hkd_code"),
                'name' => $translationAdapter->query("hkd_name"),
                'order' => 45
            ],
            [
                'code' => $translationAdapter->query("gel_code"),
                'name' => $translationAdapter->query("gel_name"),
                'order' => 46
            ],
            [
                'code' => $translationAdapter->query("qar_code"),
                'name' => $translationAdapter->query("qar_name"),
                'order' => 47
            ],
            [
                'code' => $translationAdapter->query("myr_code"),
                'name' => $translationAdapter->query("myr_name"),
                'order' => 48
            ],
            [
                'code' => $translationAdapter->query("ars_code"),
                'name' => $translationAdapter->query("ars_name"),
                'order' => 49
            ],
            [
                'code' => $translationAdapter->query("pen_code"),
                'name' => $translationAdapter->query("pen_name"),
                'order' => 50
            ],
            [
                'code' => $translationAdapter->query("isk_code"),
                'name' => $translationAdapter->query("isk_name"),
                'order' => 51
            ],
            [
                'code' => $translationAdapter->query("bob_code"),
                'name' => $translationAdapter->query("bob_name"),
                'order' => 52
            ],
            [
                'code' => $translationAdapter->query("pyg_code"),
                'name' => $translationAdapter->query("pyg_name"),
                'order' => 53
            ],
            [
                'code' => $translationAdapter->query("vef_code"),
                'name' => $translationAdapter->query("vef_name"),
                'order' => 54
            ],
            [
                'code' => $translationAdapter->query("aed_code"),
                'name' => $translationAdapter->query("aed_name"),
                'order' => 55
            ],
        ];
    }


}
