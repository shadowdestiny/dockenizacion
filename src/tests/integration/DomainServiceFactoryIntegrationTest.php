<?php
namespace EuroMillions\tests\integration;

use EuroMillions\web\services\factories\DomainServiceFactory;
use EuroMillions\web\services\factories\ServiceFactory;
use EuroMillions\tests\base\DatabaseIntegrationTestBase;

class DomainServiceFactoryIntegrationTest extends DatabaseIntegrationTestBase
{
    private $className = 'EuroMillions\web\services\factories\DomainServiceFactory';
    /** @var  DomainServiceFactory */
    private $sut;

    private $externalDependencies;

    public function setUp()
    {
        parent::setUp();
        $class = $this->className;
        $this->sut = new $class($this->getDi(), new ServiceFactory($this->getDi()));
        $this->externalDependencies = [
            'EmailService' => [
                $this->prophesize('EuroMillions\web\components\MandrillWrapper')->reveal(),
            ],
        ];
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
        $this->markTestSkipped('This test don\'t works anymore :( | Fix it? ');

        $params = isset($this->externalDependencies[$expectedService]) ? $this->externalDependencies[$expectedService] : [];
        $actual = $this->sut->$methodName(...$params);
        $this->assertInstanceOf('EuroMillions\web\services\\'.$expectedService, $actual);
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