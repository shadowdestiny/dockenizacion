<?php

namespace EuroMillions\tests\unit;

use EuroMillions\web\entities\Language;
use EuroMillions\web\services\LanguageService;
use Phalcon\Di;
use Prophecy\Argument;
use EuroMillions\tests\base\UnitTestBase;

class LanguageServiceUnitTest extends UnitTestBase
{
    protected $languageStrategy_double;
    protected $languageRepository_double;
    protected $translationAdapter_double;

    public function setUp()
    {
        parent::setUp();
        $this->languageStrategy_double = $this->getInterfaceWebDouble('ILanguageStrategy');
        $this->languageRepository_double = $this->getRepositoryDouble('LanguageRepository');
        $this->translationAdapter_double = $this->getComponentDouble('EmTranslationAdapter');
    }

    public function test_translate_calledWithProperArguments_returnTranslationAdapterResult()
    {
        $expected = 'azofaifa';
        $key = 'azo-key';
        $placeholders = null;
        $this->translationAdapter_double->query($key, $placeholders)->willReturn($expected);
        $this->languageStrategy_double->get()->willReturn('en');
        /** @var LanguageService $sut */
        $sut = new LanguageService($this->languageStrategy_double->reveal(), $this->getLanguageRepositoryStubWithDefaultLanguage()->reveal(), $this->translationAdapter_double->reveal());
        $actual = $sut->translate($key, $placeholders);
        $this->assertEquals($expected, $actual);
    }

    /**
     * method getLocale
     * when languageCodeNotFoundOnDatabase
     * should returnEnLocale
     */
    public function test_getLocale_languageCodeNotFoundOnDatabase_returnEnLocale()
    {
        $language = 'non_existing';
        $default_locale = 'lsdflksj';
        $language_entity = new Language([
            'ccode' => 'en',
            'defaultLocale' => 'en_US',
            'active' => true
        ]);
        $language_entity->setDefaultLocale($default_locale);
        $this->languageStrategy_double->get()->willReturn($language);
        $this->languageRepository_double->getActiveLanguage($language)->willReturn(null);
        $this->languageRepository_double->findOneBy(['ccode' => 'en'])->willReturn($language_entity);
        $sut = $this->getSut();
        $actual = $sut->getLocale();
        $this->assertEquals($default_locale, $actual);
    }

    protected function getSut()
    {
        return new LanguageService($this->languageStrategy_double->reveal(), $this->languageRepository_double->reveal(), $this->translationAdapter_double->reveal());
    }
}