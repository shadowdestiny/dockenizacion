<?php
namespace EuroMillions\tests\unit;

use EuroMillions\web\vo\base\StringLiteral;
use EuroMillions\tests\base\ValueObjectUnitTestBase;

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