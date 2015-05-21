<?php
namespace tests\base;

use Phalcon\Di;

abstract class RepositoryIntegrationTestBase extends IntegrationTestBase
{
    protected $sut;

    public function setUp($entity)
    {
        parent::setUp();
        $entity_manager = Di::getDefault()->get('entityManager');
        $this->sut = $entity_manager->getRepository('EuroMillions\entities\\'.$entity);
    }
}