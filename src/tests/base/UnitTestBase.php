<?php

namespace EuroMillions\tests\base;

use EuroMillions\shared\config\Namespaces;
use EuroMillions\web\entities\Language;
use Phalcon\DI;
use Prophecy\Argument;

class UnitTestBase extends \PHPUnit_Framework_TestCase
{
    use PhalconDiRelatedTest;
    use TestHelperTrait;

    const DEFAULT_ENTITY_REPOSITORY = '\Doctrine\ORM\EntityRepository';
    const DOCTRINE_EMPTY_SINGLEOBJECT_RESULT = null;
    const DOCTRINE_EMPTY_COLLECTION_RESULT = [];

    protected $original_di = null;

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
        $this->stubDIService('entityManager', $this->addMappingsToEntityManager()->reveal());
        $this->stubDIService('redisCache', $this->prophesize('\Phalcon\Cache\Backend\Redis')->reveal());
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

    protected function checkViewVarsContain($key, $value)
    {
        $view_mock = $this->getMockBuilder('\Phalcon\Mvc\View')->getMock();
        $view_mock->expects($this->once())
            ->method('setVars')
            ->with($this->callback(function ($subject) use ($key, $value) {
                return (array_key_exists($key, $subject) && $subject[$key] == $value);
            }));
        $this->stubDIService('view', $view_mock);
    }

    private function addMappingsToEntityManager()
    {
        $entityManager_stub = $this->prophesizeEntityManager();
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
        $mappings = array_merge(
            [
                Namespaces::ENTITIES_NS . 'Language' => $this->getRepositoryDouble('LanguageRepository'),
                Namespaces::ENTITIES_NS . 'TranslationDetail' => $this->getRepositoryDouble('TranslationDetailRepository'),
            ], $this->getEntityManagerStubExtraMappings());
        return $mappings;
    }

    protected function getEntityManagerStubExtraMappings()
    {
        return [];
    }

    protected function getEntityManagerRevealed()
    {
        return $this->getDi()->get('entityManager');
    }

    protected function getEntityManagerDouble()
    {
        return $this->getDi()->get('entityManager')->getProphecy();
    }

    /**
     * @param $entityManager_stub
     */
    protected function stubEntityManager($entityManager_stub)
    {
        $this->stubDiService('entityManager', $entityManager_stub->reveal());
    }

    /**
     * @return \Prophecy\Prophecy\ObjectProphecy
     */
    protected function getLanguageRepositoryStubWithDefaultLanguage()
    {
        $languageRepository_stub = $this->getRepositoryDouble('LanguageRepository');
        $language = new Language([
            'ccode' => 'en',
            'defaultLocale' => 'en_US',
            'active' => true
        ]);
        $language->initialize([
            'ccode' => 'en',
            'defaultLocale' => 'en_US',
            'active' => true
        ]);
        $languageRepository_stub->getActiveLanguage('en')->willReturn($language);
        return $languageRepository_stub;
    }

    public function booleanDataProvider()
    {
        return [
            [true],
            [false],
        ];
    }

    /**
     * @return \Prophecy\Prophecy\ObjectProphecy
     */
    public function prophesizeEntityManager()
    {
        return $this->prophesize('\Doctrine\ORM\EntityManager');
    }

    protected function iDontCareAboutFlush()
    {
        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush(Argument::any())->willReturn(null);
        $this->stubEntityManager($entityManager_stub);
    }

}