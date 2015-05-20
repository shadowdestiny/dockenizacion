<?php
include_once('TranslationMigrationBase.php');
class TranslatedStrings extends \app\migrations_data\TranslationMigrationBase
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

    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->insertTranslationTree();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
    }
}