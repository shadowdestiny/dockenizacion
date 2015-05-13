<?php
namespace tests\integration;

use app\components\EmTranslationAdapter;
use Phalcon\Di;
use tests\base\IntegrationTestBase;

class EmTranslationAdapterIntegrationTest extends IntegrationTestBase
{
    protected function getFixtures()
    {
        return [
            'translations',
            'translation_details',
            'languages',
        ];
    }

    /**
     * @dataProvider getKeysAndValues
     */
    public function test_query_calledWithProperKeys_returnProperValues($language, $key, $expected)
    {
        $di = Di::getDefault();
        $entityManager = $di->get('entityManager');

        $sut = new EmTranslationAdapter($language, $entityManager);
        $actual = $sut->query($key);
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
}