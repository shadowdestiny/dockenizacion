<?php
namespace tests\unit;

use app\components\EnvironmentDetector;
use tests\base\UnitTestBase;

class EnvironmentDetectorUnitTest extends UnitTestBase
{
    const VAR_NAME = 'VAR_NAME';

    const EXPECTED_EXCEPTION = '\app\exceptions\EnvironmentNotSetException';

    public function test_getEnvironment_calledWithEnvironmentNotSet_throw()
    {
        $this->setExpectedException(self::EXPECTED_EXCEPTION, 'Environment variable not set');
        $this->exerciseGetEnvironment();
    }

    public function test_getEnvironment_calledWithEnvironmentSet_returnSameValue()
    {
        $var_name = self::VAR_NAME;
        $expected = 'azofaifa';
        putenv("$var_name=$expected");

        $actual = $this->exerciseGetEnvironment();

        $this->assertEquals($expected, $actual);
    }

    public function test_getEnvironment_calledWithEnvironmentEmpty_throw()
    {
        putenv(self::VAR_NAME.'=');
        $this->setExpectedException(self::EXPECTED_EXCEPTION, 'Environment variable is empty');
        $this->exerciseGetEnvironment();
    }

    /**
     * @return string
     */
    protected function exerciseGetEnvironment()
    {
        $sut = new EnvironmentDetector(self::VAR_NAME);
        $actual = $sut->getEnvironment();
        return $actual;
    }
}