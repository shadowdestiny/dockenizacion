<?php


namespace tests\unit\admin;


use EuroMillions\admin\vo\ActionResult;
use EuroMillions\shareconfig\Namespaces;
use EuroMillions\web\entities\EuroMillionsDraw;
use Money\Currency;
use Money\Money;
use Prophecy\Argument;
use tests\base\PhalconDiRelatedTest;
use tests\base\UnitTestBase;

class MaintenanceDrawServiceUnitTest extends UnitTestBase
{
    use PhalconDiRelatedTest;

    private $lotteryDrawRepository_double;

    private $lotteryRepository_double;

    protected function getEntityManagerStubExtraMappings()
    {
        return [
            Namespaces::ENTITIES_NS . 'EuroMillionsDraw' => $this->lotteryDrawRepository_double,
            Namespaces::ENTITIES_NS . 'Lotter' => $this->lotteryRepository_double,

        ];
    }

    public function setUp()
    {
        $this->lotteryDrawRepository_double = $this->getRepositoryDouble('LotteryDrawRepository');
        $this->lotteryRepository_double = $this->getRepositoryDouble('LotteryRepository');
        parent::setUp();
    }


    /**
     * method updateLastResult
     * when called
     * should returnActionResultTrue
     */
    public function test_updateLastResult_called_returnActionResultTrue()
    {
        $regular_numbers = [1,2,3,4,5];
        $lucky_numbers = [1,2];
        $id_draw = 1;
        $draw_to_persist = new EuroMillionsDraw();
        $draw_to_persist->initialize([
            'draw_date'  => new \DateTime('2015-06-02 20:00:00'),
            'jackpot'    => new Money(5000, new Currency('EUR')),
            'lottery'    => 1
        ]);
        $expected = new ActionResult(true);
        $this->lotteryDrawRepository_double->findOneBy(['id' => $id_draw])->willReturn($draw_to_persist);
        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush()->shouldBeCalled();
        $this->stubEntityManager($entityManager_stub);
        $sut = $this->getSut();
        $actual = $sut->updateLastResult($regular_numbers,$lucky_numbers,$id_draw);
        $this->assertEquals($expected,$actual);
    }

    private function getSut()
    {
        return $sut = $this->getDomainAdminServiceFactory()->getMaintenanceDrawService();
    }


}