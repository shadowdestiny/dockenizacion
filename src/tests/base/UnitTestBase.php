<?php
namespace tests\base;
use Phalcon\DI;

abstract class UnitTestBase extends \PHPUnit_Framework_TestCase
{
    protected $original_di = null;
    /** @var  TestBaseHelper */
    protected $helper;
    protected function stubDIService($serviceName, $stubObject)
    {
        $di = DI::getDefault();
        if (!$this->original_di) {
            $this->original_di = clone($di);
        }
        $di->set($serviceName, $stubObject);
    }
    protected function setUp()
    {
        parent::setUp();
        $this->helper = new TestBaseHelper();
        $this->stubDiService('entityManager', $this->getMockBuilder('\Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock());
        $this->stubDiService('redisCache', $this->getMockBuilder('\Phalcon\Cache\Backend\Redis')->disableOriginalConstructor()->getMock());
    }
    protected function restoreDI()
    {
        DI::reset();
        DI::setDefault($this->original_di);
    }
    protected function tearDown()
    {
        parent::tearDown();
        if ($this->original_di) {
            $this->restoreDI();
        }
    }
    /**
     * @param $view
     */
    protected function checkViewIsRendered($view)
    {
        $view_mock = $this->getMockBuilder('\Phalcon\Mvc\View')->getMock();
        $view_mock->expects($this->once())
            ->method('pick')
            ->with($view);
        $this->stubDIService('view', $view_mock);
    }
    protected function checkViewParam($values)
    {
        $view_mock = $this->getMockBuilder('\Phalcon\Mvc\View')->getMock();
        $view_mock->expects($this->once())
            ->method('setVars')
            ->with($this->callback(function ($subject) use ($values) {
                if ($values == $subject) return true;
                $result = true;
                foreach ($values as $key => $value) {
                    if (!array_key_exists($key, $subject) || $subject[$key] != $value) {
                        $result = false;
                    }
                }
                return $result;
            }));
        $this->stubDIService('view', $view_mock);
    }
}