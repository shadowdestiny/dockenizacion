<?php
namespace tests\unit;

use EuroMillions\web\services\LanguageService;
use Phalcon\Di;
use Prophecy\Argument;
use tests\base\UnitTestBase;

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
     * when calledWithNonExistingLanguage
     * should throw
     */
    public function test_getLocale_calledWithNonExistingLanguage_throw()
    {
        $language = 'azofaifo';
        $this->languageStrategy_double->get()->willReturn($language);
        $this->languageRepository_double->getActiveLanguage($language)->willReturn(null);
        $this->setExpectedException($this->getExceptionToArgument('InvalidLanguageException'));
        $sut = $this->getSut();
        $sut->getLocale();
    }

    protected function getSut()
    {
        return new LanguageService($this->languageStrategy_double->reveal(), $this->languageRepository_double->reveal(), $this->translationAdapter_double->reveal());
    }
}