<?php
namespace tests\base;

use EuroMillions\shared\shareconfig\Namespaces;

abstract class ValueObjectUnitTestBase extends UnitTestBase
{
    abstract public function getSut($value);

    abstract public function getClassName();

    abstract public function getNativeValue();

    abstract public function getBadNativeValue();

    abstract public function getStringRepresentation();

    /**
     * method fromNative
     * when called
     * should returnNewInstance
     */
    public function test_fromNative_called_returnNewInstance()
    {
        $this->assertInstanceOf(Namespaces::VALUEOBJECTS_NS . $this->getClassName(), $this->getSut($this->getNativeValue()));
    }

    /**
     * method toNative
     * when called
     * should returnNativeValue
     */
    public function test_toNative_called_returnNativeValue()
    {
        $sut = $this->getSut($this->getNativeValue());
        $actual = $sut->toNative();
        $this->assertEquals($this->getNativeValue(), $actual);
    }

    /**
     * method __toString
     * when called
     * should returnProperString
     */
    public function test___toString_called_returnProperString()
    {
        $sut = $this->getSut($this->getNativeValue());
        $actual = (string)$sut;
        $this->assertEquals($this->getStringRepresentation(), $actual);
    }

    /**
     * method __construct
     * when calledWithBadValue
     * should throw
     */
    public function test___construct_calledWithBadValue_throw()
    {
        $this->setExpectedException($this->getExceptionToArgument('InvalidNativeArgumentException'));
        $this->getSut($this->getBadNativeValue());
    }
}