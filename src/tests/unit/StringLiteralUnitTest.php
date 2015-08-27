<?php
namespace tests\unit;

use EuroMillions\vo\base\StringLiteral;
use tests\base\ValueObjectUnitTestBase;

class StringLiteralUnitTest extends ValueObjectUnitTestBase
{

    public function getSut($value)
    {
        return StringLiteral::fromNative($value);
    }

    public function getClassName()
    {
        return 'base\StringLiteral';
    }

    public function getNativeValue()
    {
        return 'dkjlsdkfjsl';
    }

    public function getBadNativeValue()
    {
        return 3892;
    }

    public function getStringRepresentation()
    {
        return 'dkjlsdkfjsl';
    }
}