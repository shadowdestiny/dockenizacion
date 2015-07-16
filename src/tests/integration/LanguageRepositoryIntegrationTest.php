<?php
namespace tests\integration;

use EuroMillions\entities\Language;
use tests\base\RepositoryIntegrationTestBase;

class LanguageRepositoryIntegrationTest extends RepositoryIntegrationTestBase
{
    /** @var  \EuroMillions\repositories\LanguageRepository */
    protected $sut;

    protected function getFixtures()
    {
        return [
            'languages',
        ];
    }

    public function setUp()
    {
        parent::setUp('Language');
    }

    public function test_getActiveLanguages_called_returnProperResults()
    {
        $expected = [1, 2];
        $actual = $this->sut->getActiveLanguages();
        $this->assertEquals($expected, $this->getIdsFromArrayOfObjects($actual));
    }

    protected function getIdsFromArrayOfObjects(array $objects)
    {
        $result = array();
        /** @var \EuroMillions\interfaces\IEntity $object */
        foreach ($objects as $object) {
            $result[] = $object->getId();
        }
        return $result;
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

}