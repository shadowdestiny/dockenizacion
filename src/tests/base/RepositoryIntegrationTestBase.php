<?php
namespace EuroMillions\tests\base;

use Phalcon\Di;

abstract class RepositoryIntegrationTestBase extends DatabaseIntegrationTestBase
{
    protected $sut;

    public function setUp()
    {
        parent::setUp();
        $entity_manager = Di::getDefault()->get('entityManager');
        $this->sut = $entity_manager->getRepository('EuroMillions\web\entities\\'.$this->getEntity());
    }

    protected abstract function getEntity();
}