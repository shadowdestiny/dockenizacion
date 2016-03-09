<?php
namespace EuroMillions\tests\integration;

use EuroMillions\web\entities\Language;
use EuroMillions\tests\base\RepositoryIntegrationTestBase;

class LanguageRepositoryIntegrationTest extends RepositoryIntegrationTestBase
{
    /** @var  \EuroMillions\web\repositories\LanguageRepository */
    protected $sut;

    protected function getFixtures()
    {
        return [
            'languages',
        ];
    }


    public function test_getActiveLanguages_called_returnProperResults()
    {
        $expected = [1, 2];
        $actual = $this->sut->getActiveLanguages();
        $this->assertEquals($expected, $this->getIdsFromArrayOfObjects($actual));
    }

    /**
     * method getActiveLanguage
     * when calledWithExistingLanguage
     * should returnProperEntity
     */
    public function test_getActiveLanguage_calledWithExistingLanguage_returnProperEntity()
    {
        $language = 'es';
        $actual = $this->sut->getActiveLanguage($language);
        $expected = new Language();
        $expected->initialize([
            'id'=> 2,
            'ccode' => 'es',
            'active' => true,
            'defaultLocale' => 'es_ES'
        ]);
        $this->assertEquals($expected, $actual);
    }


    protected function getEntity()
    {
        return 'Language';
    }
}