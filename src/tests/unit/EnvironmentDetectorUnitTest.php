<?php
namespace tests\unit;

use EuroMillions\sharecomponents\EnvironmentDetector;
use tests\base\UnitTestBase;

class EnvironmentDetectorUnitTest extends UnitTestBase
{
    const VAR_NAME = 'VAR_NAME';

    const EXPECTED_EXCEPTION = '\EuroMillions\shareconfig\exceptions\EnvironmentNotSetException';

    /** @var  EnvironmentDetector */
    protected $sut;

    public function setUp()
    {
        $this->sut = new EnvironmentDetector(self::VAR_NAME);
        parent::setUp();
    }

    public function tearDown()
    {
        putenv(self::VAR_NAME);
        parent::tearDown();
    }

    public function test_get_calledWithEnvironmentNotSet_throw()
    {
        $this->setExpectedException(self::EXPECTED_EXCEPTION, 'Environment variable not set');
        $this->sut->get();
    }

    public function test_get_calledWithEnvironmentSet_returnSameValue()
    {
        $expected = 'vagrant';
        $this->setEnvironment($expected);

        $actual = $this->sut->get();

        $this->assertEquals($expected, $actual);
    }

    public function test_get_calledWithEnvironmentEmpty_throw()
    {
        $this->setEnvironment('');
        $this->setExpectedException(self::EXPECTED_EXCEPTION, 'Environment variable is empty');
        $this->sut->get();
    }


    public function test_setDefault_calledWithEnvironmentNotSet_pubEnv()
    {
        $expected = EnvironmentDetector::DEFAULT_ENV;
        $this->sut->setDefault();
        $actual = getenv(self::VAR_NAME);
        $this->assertEquals($expected, $actual);
    }

    public function test_setDefault_calledWithEnvironmentSet_throw()
    {
        $this->setEnvironment('iesljsg');
        $this->setExpectedException(self::EXPECTED_EXCEPTION, 'Trying to set an environment where one is yet set');
        $this->sut->setDefault();
    }


    public function test_isEnvSet_calledWhenNotSet_returnFalse()
    {
        $actual = $this->sut->isEnvSet();
        $this->assertFalse($actual);
    }

    public function test_isEnvSet_calledWhenSet_returnTrue()
    {
        $this->setEnvironment('sdklfjs');
        $actual = $this->sut->isEnvSet();
        $this->assertTrue($actual);
    }

    /**
     * method get
     * when calledWhenAnInvalidEnvironmentHasBeenSet
     * should throwException
     */
    public function test_get_calledWhenAnInvalidEnvironmentHasBeenSet_throwException()
    {
        $bad = 'AZOFAIFA';
        $this->setExpectedException(self::EXPECTED_EXCEPTION, 'Invalid environment var set: "'.$bad.'"');
        $this->setEnvironment($bad);
        $this->sut->get();
    }

    /**
     * @param $env
     */
    protected function setEnvironment($env)
    {
        putenv(self::VAR_NAME . '=' . $env);
    }
}