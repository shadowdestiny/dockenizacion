<?php
namespace tests\base;

use Phalcon\DI;

class UnitTestBase extends \PHPUnit_Framework_TestCase
{
    const DEFAULT_ENTITY_REPOSITORY = '\Doctrine\ORM\EntityRepository';
    const REPOSITORIES_NAMESPACE = 'EuroMillions\repositories\\';
    const ENTITIES_NAMESPACE = 'EuroMillions\entities\\';
    protected $original_di = null;
    /** @var  TestBaseHelper */
    protected $helper;

    protected function stubDIService($serviceName, $stubObject)
    {
        $di = $this->getDi();
        if (!$this->original_di) {
            $this->original_di = clone($di);
        }
        $di->set($serviceName, $stubObject);
    }

    protected function setUp()
    {
        parent::setUp();
        $this->helper = new TestBaseHelper();
        $this->stubDiService('entityManager', $this->getEntityManagerStub()->reveal());
        $this->stubDiService('redisCache', $this->prophesize('\Phalcon\Cache\Backend\Redis')->reveal());
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

    protected function getDi()
    {
        return Di::getDefault();
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

    public function getEntityManagerStub()
    {
        $entityManager_stub = $this->prophesize('\Doctrine\ORM\EntityManager');
        $mappings = $this->getEntityManagerStubMappings();
        foreach ($mappings as $entity_name => $repository_double) {
            $entityManager_stub
                ->getRepository($entity_name)
                ->willReturn(
                    $repository_double
                );
        }
        return $entityManager_stub;
    }

    private function getEntityManagerStubMappings()
    {
        return array_merge(
            [
                self::ENTITIES_NAMESPACE.'Language'          => $this->prophesize(self::REPOSITORIES_NAMESPACE.'LanguageRepository'),
                self::ENTITIES_NAMESPACE.'TranslationDetail' => $this->prophesize(self::REPOSITORIES_NAMESPACE.'TranslationDetailRepository'),
            ], $this->getEntityManagerStubExtraMappings());
    }

    protected function getEntityManagerStubExtraMappings()
    {
        return [];
    }

    protected function getEntityManagerDouble()
    {
        $entityManager_double = $this->getDi()->get('entityManager')->getProphecy();
        return $entityManager_double;
    }

    /**
     * @param $entityManager_stub
     */
    protected function stubEntityManager($entityManager_stub)
    {
        $this->stubDiService('entityManager', $entityManager_stub->reveal());
    }

}