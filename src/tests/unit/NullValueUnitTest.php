<?php
namespace tests\unit;

use EuroMillions\web\vo\NullPortNumber;
use tests\base\ValueObjectUnitTestBase;

class NullValueUnitTest extends ValueObjectUnitTestBase
{
    public function getSut($value)
    {
       return NullPortNumber::fromNative($value);
    }

    public function getClassName()
    {
        return 'NullPortNumber';
    }

    public function getNativeValue()
    {
        return null;
    }

    public function getStringRepresentation()
    {
        return '';
    }

    public function getBadNativeValue()
    {
        return 3;
    }
}