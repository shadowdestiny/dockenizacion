<?php
namespace tests\unit;

use app\services\LanguageService;
use Phalcon\Di;
use tests\base\UnitTestBase;

class LanguageServiceUnitTest extends UnitTestBase
{
    public function test_translate_calledWithProperArguments_returnTranslationAdapterResult()
    {
        $expected = 'azofaifa';
        $key = 'azo-key';
        $placeholders = null;
        $entity_manager = Di::getDefault()->get('entityManager');
        /** @var \app\components\EmTranslationAdapter|\PHPUnit_Framework_MockObject_MockObject $translation_adapter_stub */
        $translation_adapter_stub = $this->getMockBuilder('\app\components\EmTranslationAdapter')->disableOriginalConstructor()->getMock();
        $translation_adapter_stub->expects($this->any())
            ->method('_')
            ->with($key, $placeholders)
            ->will($this->returnValue($expected));
        /** @var LanguageService $sut */
        $sut = new LanguageService('en', $entity_manager, $translation_adapter_stub);
        $actual = $sut->translate($key, $placeholders);
        $this->assertEquals($expected, $actual);
    }
}