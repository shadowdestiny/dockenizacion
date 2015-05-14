<?php
namespace tests\integration;

use Phalcon\Di;
use tests\base\IntegrationTestBase;

class LanguageRepositoryIntegrationTest extends IntegrationTestBase
{
    /** @var \app\repositories\LanguageRepository */
    protected $sut;

    protected function getFixtures()
    {
        return [
            'languages',
        ];
    }

    public function setUp()
    {
        parent::setUp();
        /** @var \Doctrine\ORM\EntityManager $entity_manager */
        $entity_manager = Di::getDefault()->get('entityManager');
        $this->sut = $entity_manager->getRepository('app\entities\Language');
    }

    public function test_getActiveLanguages_called_returnProperResults()
    {
        $expected = [1,2];
        $actual = $this->sut->getActiveLanguages();
        $this->assertEquals($expected, $this->getIdsFromArrayOfObjects($actual));
    }

    protected function getIdsFromArrayOfObjects(array $objects)
    {
        $result = array();
        /** @var \app\interfaces\IEntity $object */
        foreach ($objects as $object) {
            $result[] = $object->getId();
        }
        return $result;
    }

}