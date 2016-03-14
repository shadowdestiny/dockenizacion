<?php


namespace EuroMillions\tests\unit\admin;


use EuroMillions\admin\services\MaintenanceDrawService;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\shared\config\Namespaces;
use EuroMillions\web\entities\EuroMillionsDraw;
use Money\Currency;
use Money\Money;
use Prophecy\Argument;
use EuroMillions\tests\base\PhalconDiRelatedTest;
use EuroMillions\tests\base\UnitTestBase;

class MaintenanceDrawServiceUnitTest extends UnitTestBase
{
    use PhalconDiRelatedTest;

    private $lotteryDrawRepository_double;

    private $lotteryRepository_double;

    protected function getEntityManagerStubExtraMappings()
    {
        return [
            Namespaces::ENTITIES_NS . 'EuroMillionsDraw' => $this->lotteryDrawRepository_double,
            Namespaces::ENTITIES_NS . 'Lottery' => $this->lotteryRepository_double,

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
        $money = new Money(5000, new Currency('EUR'));
        $draw_to_persist->initialize([
            'draw_date'  => new \DateTime('2015-06-02 20:00:00'),
            'jackpot'    => $money,
            'lottery'    => 1
        ]);
        $expected = new ActionResult(true);
        $this->lotteryDrawRepository_double->findOneBy(['id' => $id_draw])->willReturn($draw_to_persist);
        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush()->shouldBeCalled();
        $this->stubEntityManager($entityManager_stub);
        $sut = $this->getSut();
        $actual = $sut->updateLastResult($regular_numbers,$lucky_numbers,$money,$id_draw);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method updaLastResult
     * when called
     * should returnActionResultFalse
     */
    public function test_updaLastResult_called_returnActionResultFalse()
    {
        //$this->markTestSkipped();
        $regular_numbers = [1,2,3,4,5];
        $lucky_numbers = [1,2];
        $id_draw = 1;
        $draw_to_persist = new EuroMillionsDraw();
        $money = new Money(5000, new Currency('EUR'));
        $draw_to_persist->initialize([
            'draw_date'  => new \DateTime('2015-06-02 20:00:00'),
            'jackpot'    => $money,
            'lottery'    => 1
        ]);
        $expected = new ActionResult(false);
        $this->lotteryDrawRepository_double->findOneBy(['id' => $id_draw])->willReturn($draw_to_persist);
        $entityManager_stub = $this->getEntityManagerDouble();
        $entityManager_stub->flush()->willThrow(new \Exception());
        $this->stubEntityManager($entityManager_stub);
        $sut = $this->getSut();
        $actual = $sut->updateLastResult($regular_numbers,$lucky_numbers,$money,$id_draw);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method getDrawByDate
     * when calledWithValidDate
     * should returnActionResultWithOnceDraw
     */
    public function test_getDrawByDate_calledWithValidDate_returnActionResultWithOnceDraw()
    {
        $today = new \DateTime('2015-11-03');
        $draw_to_persist = new EuroMillionsDraw();
        $sut = $this->getSut();
        $this->lotteryDrawRepository_double->findOneBy(['draw_date' => $today])->willReturn($draw_to_persist);
        $actual = $sut->getDrawByDate($today);
        $this->assertEquals(true,$actual->success());
    }

    /**
     * method getDrawByDate
     * when calledWithInvalidDate
     * should returnActionResultFalse
     */
    public function test_getDrawByDate_calledWithInvalidDate_returnActionResultFalse()
    {
        $today = new \DateTime('2015-11-04');
        $sut = $this->getSut();
        $this->lotteryDrawRepository_double->findOneBy(['draw_date' => $today])->willReturn(self::DOCTRINE_EMPTY_SINGLEOBJECT_RESULT);
        $actual = $sut->getDrawByDate($today);
        $this->assertEquals(false,$actual->success());
    }


    private function getSut()
    {
        return new MaintenanceDrawService($this->getEntityManagerRevealed());
    }
}