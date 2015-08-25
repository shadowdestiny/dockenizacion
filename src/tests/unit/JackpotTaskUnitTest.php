<?php
namespace tests\unit;

use EuroMillions\config\Namespaces;
use EuroMillions\tasks\JackpotTask;
use tests\base\UnitTestBase;

class JackpotTaskUnitTest extends UnitTestBase
{

    protected function getEntityManagerStubExtraMappings()
    {
        return [
            'EuroMillions\entities\EuroMillionsDraw' => self::DEFAULT_ENTITY_REPOSITORY,
            'EuroMillions\entities\Lottery' => Namespaces::REPOSITORIES_NS.'LotteryRepository',
        ];
    }

    /**
     * method updatePreviousAction
     * when called
     * should callServiceWithDateOfPreviousDraw
     */
    public function test_updatePreviousAction_called_callServiceWithDateOfPreviousDraw()
    {
        $today = new \DateTime('2015-06-12 10:37:08');
        $lottery_name = 'EuroMillions';

        $lotteriesDataService_double = $this->getMockBuilder('\EuroMillions\services\LotteriesDataService')->disableOriginalConstructor()->getMock();
        $lotteriesDataService_double->expects($this->any())
            ->method('getLastDrawDate')
            ->with($lottery_name, $today)
            ->will($this->returnValue(new \DateTime('2015-06-09 20:00:00')));

        $lotteriesDataService_double->expects($this->once())
            ->method('updateNextDrawJackpot')
            ->with($lottery_name, new \DateTime('2015-06-09 19:59:00'));

        $sut = new JackpotTask();
        $sut->initialize($lotteriesDataService_double);
        $sut->updatePreviousAction($today);
    }
}