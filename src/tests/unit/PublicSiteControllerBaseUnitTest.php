<?php
namespace tests\unit;

use EuroMillions\controllers\PublicSiteControllerBase;
use EuroMillions\entities\Language;
use EuroMillions\services\LotteriesDataService;
use Money\Currency;
use Money\Money;
use Phalcon\Di;
use tests\base\UnitTestBase;

class PublicSiteControllerBaseUnitTest extends UnitTestBase
{
    public function test_afterExecuteRoute_called_setActiveLanguagesInView()
    {
        $lang1 = new Language();
        $lang2 = new Language();
        $lang3 = new Language();
        $attributes1 = ['id' => 1, 'ccode' => 'en', 'active'=>1];
        $attributes2 = ['id' => 2, 'ccode' => 'es', 'active'=>1];
        $attributes3 = ['id' => 3, 'ccode' => 'fr', 'active'=>1];
        $lang1->initialize($attributes1);
        $lang2->initialize($attributes2);
        $lang3->initialize($attributes3);

        $active_languages = [$lang1, $lang2, $lang3];

        $language_service_double = $this->getMockBuilder('\EuroMillions\services\LanguageService')->disableOriginalConstructor()->getMock();
        $language_service_double->expects($this->any())
            ->method('activeLanguages')
            ->will($this->returnValue($active_languages));

        $this->stubDIService('language', $language_service_double);

        $expected1 = $this->getStandardObject($attributes1);
        $expected2 = $this->getStandardObject($attributes2);
        $expected3 = $this->getStandardObject($attributes3);

        $sut = new PublicSiteControllerBase();
        /** @var LotteriesDataService|\PHPUnit_Framework_MockObject_MockObject $lotteriesDataService_stub */
        $lotteriesDataService_stub = $this->getMockBuilder('\EuroMillions\services\LotteriesDataService')->getMock();
        $lotteriesDataService_stub->expects($this->any())
            ->method('getNextJackpot')
            ->will($this->returnValue(new Money(100, new Currency('EUR'))));
        $sut->initialize($lotteriesDataService_stub);
        $this->checkViewParam(['languages' => [$expected1, $expected2, $expected3]]);
        $sut->afterExecuteRoute();
    }

    protected function getStandardObject($attributes)
    {
        $result = new \stdClass();
        foreach ($attributes as $property => $value) {
            $result->$property = $value;
        }
        return $result;
    }

}