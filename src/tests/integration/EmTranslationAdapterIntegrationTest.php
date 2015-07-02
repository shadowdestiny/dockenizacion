<?php
namespace tests\integration;

use EuroMillions\components\EmTranslationAdapter;
use Phalcon\Di;
use tests\base\DatabaseIntegrationTestBase;

class EmTranslationAdapterIntegrationTest extends DatabaseIntegrationTestBase
{
    /** @var  EmTranslationAdapter */
    protected $sut;

    protected function getFixtures()
    {
        return [
            'languages',
            'translations',
            'translation_details',
        ];
    }

    public function setUp()
    {
        parent::setUp();
        $di = Di::getDefault();
        $entityManager = $di->get('entityManager');
        $this->sut = new EmTranslationAdapter('en', $entityManager->getRepository('\EuroMillions\entities\TranslationDetail'));
    }

    /**
     * @dataProvider getKeysAndValues
     */
    public function test_query_calledWithProperKeys_returnProperValues($language, $key, $expected)
    {
        $this->sut->setLanguage($language);
        $actual = $this->sut->query($key);
        $this->assertEquals($expected, $actual);
    }

    public function getKeysAndValues()
    {
        return [
            ['en', 'latest-articles', 'Latest Articles'],
            ['es', 'latest-articles', 'Últimas Noticias'],
            ['en', 'responsible-gaming1', 'Juego responsable'],
        ];
    }

    /**
     * @dataProvider getKeysAndValuesWithPlaceholders
     */
    public function test_underscoreFunction_calledWithProperKeysAndPlaceholders_returnProperValues($language, $key, $placeHolders, $expected)
    {
        $this->sut->setLanguage($language);
        $actual = $this->sut->_($key, $placeHolders);
        $this->assertEquals($expected, $actual);
    }

    public function getKeysAndValuesWithPlaceholders()
    {
        return [
            ['en', 'number_wins', ['wins' => 35], 'You won 35 times'],
            ['es', 'number_wins', ['wins' => 4], 'Has ganado 4 veces'],
        ];
    }
}