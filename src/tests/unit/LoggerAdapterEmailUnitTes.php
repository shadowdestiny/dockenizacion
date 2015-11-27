<?php


namespace tests\unit;


use EuroMillions\web\components\Logger\Adapter\Email as EmailAdapter;
use Phalcon\Logger;
use Prophecy\Argument;
use tests\base\UnitTestBase;

class LoggerAdapterEmailUnitTest extends UnitTestBase
{

    protected $emailService_double;

    public function setUp()
    {
        parent::setUp();
        $this->emailService_double = $this->getServiceDouble('EmailService');
    }

    /**
     * method testEmailAdapter
     * when called
     * should callLogInternalMethodClass
     */
    public function test_testEmailAdapter_called_callLogInternalMethodClass()
    {
        $this->emailService_double->sendLog(Argument::any(),Argument::any(),Argument::any(),Argument::any())->shouldBeCalled();
        $sut = new EmailAdapter('test', $this->emailService_double->reveal());
        $sut->setLogLevel(Logger::ERROR);
        $sut->error('Testing error critical level in adapter email');
    }


}