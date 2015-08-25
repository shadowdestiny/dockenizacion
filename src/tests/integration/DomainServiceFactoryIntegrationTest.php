<?php
namespace tests\integration;

use EuroMillions\services\DomainServiceFactory;
use tests\base\DatabaseIntegrationTestBase;

class DomainServiceFactoryIntegrationTest extends DatabaseIntegrationTestBase
{
    private $className = 'EuroMillions\services\DomainServiceFactory';
    /** @var  DomainServiceFactory */
    private $sut;

    public function setUp()
    {
        parent::setUp();
        $class = $this->className;
        $this->sut = new $class($this->getDi());
    }

    /**
     * method getX
     * when called
     * should returnProperService
     * @dataProvider getGetXMethodsAndExpectedService
     * @param $methodName
     * @param $expectedService
     */
    public function test_getX_called_returnProperService($methodName, $expectedService)
    {
        $actual = $this->sut->$methodName();
        $this->assertInstanceOf('EuroMillions\services\\'.$expectedService, $actual);
    }

    public function getGetXMethodsAndExpectedService()
    {
        $methods = get_class_methods($this->className);
        $result = [];
        foreach ($methods as $method_name) {
            $match = preg_match('/get([A-Z][a-zA-Z]+Service)/', $method_name, $matches);
            if ($match) {
                $result[] = [$method_name, $matches[1]];
            }
        }
        return $result;
    }

    protected function getFixtures()
    {
        return [];
    }
}