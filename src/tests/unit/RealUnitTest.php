<?php
namespace EuroMillions\tests\unit;
use EuroMillions\web\vo\base\Real;
use EuroMillions\tests\base\ValueObjectUnitTestBase;

class RealUnitTest extends ValueObjectUnitTestBase
{

    public function getSut($value)
    {
        return Real::fromNative($value);
    }

    public function getClassName()
    {
        return 'base\Real';
    }

    public function getNativeValue()
    {
        return 1.29848;
    }

    public function getStringRepresentation()
    {
        return "1.29848";
    }

    public function getBadNativeValue()
    {
        return "9204.4es92";
    }
}