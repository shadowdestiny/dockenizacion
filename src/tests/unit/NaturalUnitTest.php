<?php
namespace EuroMillions\tests\unit;

use EuroMillions\web\vo\base\Natural;
use EuroMillions\tests\base\ValueObjectUnitTestBase;

class NaturalUnitTest extends ValueObjectUnitTestBase
{

    public function getSut($value)
    {
        return Natural::fromNative($value);
    }

    public function getClassName()
    {
        return 'base\Natural';
    }

    public function getNativeValue()
    {
        return 384;
    }

    public function getStringRepresentation()
    {
        return '384';
    }

    public function getBadNativeValue()
    {
       return -392;
    }
}