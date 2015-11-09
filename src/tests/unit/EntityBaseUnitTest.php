<?php
namespace tests\unit;

use EuroMillions\web\entities\EntityBase;
use EuroMillions\web\entities\Language;
use EuroMillions\web\entities\Lottery;
use tests\base\UnitTestBase;

class EntityBaseUnitTest extends UnitTestBase
{
    /** @var  EntityBase */
    protected $sut;

    public function setUp()
    {
        parent::setUp();
        $this->sut = new Language();
    }

    public function test_initialize_calledWithArgument_setProperties()
    {
        $this->sut->initialize(['id' => 1, 'ccode' => 'es', 'active' => 3]);
        $this->assertEquals('1es3', $this->sut->getId().$this->sut->getCcode() . $this->sut->getActive());
    }

    public function test_initialize_calledWithWrongPropertyName_throw()
    {
        $bad_name = 'badproperty';
        $this->setExpectedException($this->getExceptionToArgument('BadEntityInitializationException'), 'Bad property name: "' . $bad_name . '"');

        $this->sut->initialize(['ccode' => 'es', $bad_name => 'anyway']);
    }

    public function test_toValueObject_called_transformEntityToValueObject()
    {
        $this->sut->initialize(['ccode' => 'en', 'active' => 1, 'defaultLocale' => 'en_US']);
        $expected = new \stdClass();
        $expected->ccode = 'en';
        $expected->active = 1;
        $expected->id = null;
        $expected->defaultLocale = 'en_US';
        $actual = $this->sut->toValueObject();
        $this->assertEquals($expected, $actual);
    }

    /**
     * method initialize
     * when calledWithFieldThatHasUnderscore
     * should setProperties
     */
    public function test_initialize_calledWithFieldThatHasUnderscore_setProperties()
    {
        $sut = new Lottery();
        $sut->initialize([
                'id' => 1,
                'name' => 'EuroMillions',
                'active' => 1,
                'frequency' => 'frequency',
                'draw_time' => 'time'
            ]
        );
        $this->assertEquals('time', $sut->getDrawTime());
    }
}