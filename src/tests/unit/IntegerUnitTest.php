<?php
namespace EuroMillions\tests\unit;

use EuroMillions\web\vo\base\Integer;
use EuroMillions\tests\base\ValueObjectUnitTestBase;

class IntegerUnitTest extends ValueObjectUnitTestBase
{

    public function getSut($value)
    {
        return Integer::fromNative($value);
    }

    public function getClassName()
    {
        return 'base\Integer';
    }

    public function getNativeValue()
    {
        return -482;
    }

    public function getStringRepresentation()
    {
        return '-482';
    }

    public function getBadNativeValue()
    {
        return 13.4893;
    }
}