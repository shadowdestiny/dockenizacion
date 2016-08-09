<?php
use Phinx\Seed\AbstractSeed;

class TranslatedStringsSeeder extends AbstractSeed
{
    public $translation_tree = [
        'en' => [
            'en'             => 'English',
            'es'             => 'Spanish',
            'fr'             => 'French',
            'de'             => 'German',
            'nl'             => 'Dutch',
            'sw'             => 'Swahili',
            'Win top prizes' => 'Win top prizes',
            'Play Games'     => 'Play Games',
            'Winning'        => 'Winning',
            'Numbers'        => 'Numbers',
            'Hello, Sign in' => 'Hello, Sign in',
            'Your account'   => 'Your account',
            'Cart'           => 'Cart'
        ],
        'es' => [
            'en'             => 'Inglés',
            'es'             => 'Español',
            'fr'             => 'Francés',
            'de'             => 'Alemán',
            'nl'             => 'Holandés',
            'sw'             => 'Swahili',
            'Win top prizes' => 'Gana premios enormes',
            'Play Games'     => 'Juega',
            'Winning'        => 'Ganar',
            'Numbers'        => 'Números',
            'Hello, Sign in' => 'Hola, haz login',
            'Your account'   => 'Tu cuenta',
            'Cart'           => 'Carrito'
        ],
    ];

    public $config_language = [
        'en' => [
            'active' => 1,
            'defaultLocale' => 'en_US'
        ],
        'es' => [
            'active' => 0,
            'defaultLocale' => 'es_ES'
        ]
    ];


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
        foreach ($this->translation_tree as $language => $data) {
            $language_values[] = "('$language')";
            foreach ($data as $key => $value) {
                $translation_values[] = "('$key')";
                $str = "INSERT INTO translation_details"
                    . " (`translation_id`, `lang`, `value`, `language_id`)"
                    . " SELECT t.id, '$language', '$value', l.id"
                    . " FROM translations t, languages l"
                    . " WHERE t.`translationKey` = '$key' AND l.ccode = '$language'";
                $translation_details_insert[] = $str;
            }
        }
        $language_values = implode(',', $language_values);
        $translation_values = implode(',', $translation_values);

        $sql = "INSERT IGNORE INTO languages (`ccode`) VALUES $language_values";
        $this->execute($sql);
        $sql = "INSERT IGNORE INTO translations (`translationKey`) VALUES $translation_values";
        $this->execute($sql);
        $insert = implode(';', $translation_details_insert);
        $this->execute($insert);
        $this->execute("UPDATE languages SET active = 1 WHERE ccode = 'en' OR ccode = 'es'");
        $this->execute("UPDATE languages SET defaultLocale = 'en_US' WHERE ccode = 'en';");
        $this->execute("UPDATE languages SET defaultLocale = 'es_ES' WHERE ccode = 'es';");
    }
}
