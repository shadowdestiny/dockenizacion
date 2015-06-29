<?php
namespace tests\functional;

use Doctrine\ORM\EntityManager;
use EuroMillions\entities\Lottery;
use EuroMillions\services\external_apis\LoteriasyapuestasDotEsApi;
use Phalcon\Di;
use tests\base\DatabaseIntegrationTestBase;

class LoteriasyapuestasDotEsApiFunctionalTest extends DatabaseIntegrationTestBase
{
    /**
     * method getJackpotForDate
     * when called
     * should returnsAcceptableJackpot
     */
    public function test_getJackpotForDate_called_returnsAcceptableJackpot()
    {
        /** @var EntityManager $entity_manager */
        $entity_manager = DI::getDefault()->get('entityManager');
        $lottery_repository = $entity_manager->getRepository('\EuroMillions\entities\Lottery');
        /** @var Lottery $lottery */
        $lottery = $lottery_repository->findOneBy(['name'=>'EuroMillions']);
        $sut = new LoteriasyapuestasDotEsApi();
        $actual = $sut->getJackpotForDate($lottery->getName(), $lottery->getNextDrawDate()->format("Y-m-d"));
        $this->assertGreaterThanOrEqual(1500000000, $actual->getAmount());
    }

    /**
     * Child classes must implement this method. Return empty array if no fixtures are needed
     * @return array
     */
    protected function getFixtures()
    {
        return [
            'lotteries',
        ];
    }
}