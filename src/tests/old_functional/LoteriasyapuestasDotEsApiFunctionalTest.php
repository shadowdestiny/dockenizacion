<?php
namespace EuroMillions\tests\functional;

use Doctrine\ORM\EntityManager;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\services\external_apis\LoteriasyapuestasDotEsApi;
use Phalcon\Di;
use EuroMillions\tests\base\DatabaseIntegrationTestBase;

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
        $lottery_repository = $entity_manager->getRepository($this->getEntitiesToArgument('Lottery'));
        /** @var Lottery $lottery */
        $lottery = $lottery_repository->findOneBy(['name'=>'EuroMillions']);
        $sut = new LoteriasyapuestasDotEsApi();
        $actual = $sut->getJackpotForDate($lottery->getName(), $lottery->getNextDrawDate()->format("Y-m-d"));
        $this->assertGreaterThanOrEqual(1500000000, $actual->getAmount());
    }


    /**
     * method getResultForDate
     * when called
     * should returnsResultsForDate
     */
    public function test_getResultForDate_called_returnsResultsForDate()
    {
        /** @var EntityManager $entity_manager */
        $entity_manager = DI::getDefault()->get('entityManager');
        $lottery_repository = $entity_manager->getRepository($this->getEntitiesToArgument('Lottery'));
        /** @var Lottery $lottery */
        $lottery = $lottery_repository->findOneBy(['name'=>'EuroMillions']);
        $sut = new LoteriasyapuestasDotEsApi();
        $actual = $sut->getResultForDate($lottery->getName(), $lottery->getLastDrawDate()->format("Y-m-d"));
        $this->assertArrayHasKey('regular_numbers',$actual);
        $this->assertArrayHasKey('lucky_numbers',$actual);
    }

    public function testGetRaffleForDate()
    {
        $entity_manager = DI::getDefault()->get('entityManager');
        $lottery_repository = $entity_manager->getRepository($this->getEntitiesToArgument('Lottery'));
        /** @var Lottery $lottery */
        $lottery = $lottery_repository->findOneBy(['name' => 'EuroMillions']);
        $sut = new LoteriasyapuestasDotEsApi();
        $actual = $sut->getRaffleForDate($lottery->getName(), $lottery->getLastDrawDate()->format("Y-m-d"));
        $this->assertArrayHasKey('raffle_numbers', $actual);
    }
    
    /**
     * method getResultBreakDownForDate
     * when called
     * should returnBreakdownForDate
     */
    public function test_getResultBreakDownForDate_called_returnBreakdownForDate()
    {
        /** @var EntityManager $entity_manager */
        $entity_manager = DI::getDefault()->get('entityManager');
        $lottery_repository = $entity_manager->getRepository($this->getEntitiesToArgument('Lottery'));
        /** @var Lottery $lottery */
        $lottery = $lottery_repository->findOneBy(['name'=>'EuroMillions']);
        $sut = new LoteriasyapuestasDotEsApi();
        $actual = $sut->getResultBreakDownForDate($lottery->getName(),$lottery->getLastDrawDate()->format("Y-m-d"));
        $this->assertArrayHasKey('category_one',$actual);
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