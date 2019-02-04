<?php


namespace EuroMillions\tests\integration;


use EuroMillions\tests\helpers\mothers\LotteryMother;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\repositories\PlayConfigRepository;
use EuroMillions\web\vo\EuroMillionsLine;
use EuroMillions\tests\base\DatabaseIntegrationTestBase;
use EuroMillions\tests\base\EuroMillionsResultRelatedTest;

class PlayConfigRepositoryIntegrationTest extends DatabaseIntegrationTestBase
{

    use EuroMillionsResultRelatedTest;

    /** @var  PlayConfigRepository */
    protected $sut;

    /**
     * Child classes must implement this method. Return empty array if no fixtures are needed
     * @return array
     */
    protected function getFixtures()
    {
        return [
            'lotteries',
            'users',
            'play_configs'
        ];
    }

    public function setUp()
    {
        parent::setUp();
        $this->sut = $this->entityManager->getRepository($this->getEntitiesToArgument('PlayConfig'));
    }

    /**
     * method add
     * when called
     * should storeCorrectlyInDatabase
     */
    public function test_add_called_storeCorrectlyInDatabase()
    {
        $user = $this->entityManager->find($this->getEntitiesToArgument('User'), '9098299B-14AC-4124-8DB0-19571EDABE57');
        $regular_numbers = [1, 2, 3, 4, 5];
        $lucky_numbers = [5, 8];
        $euroMillionsLine = new EuroMillionsLine($this->getRegularNumbers($regular_numbers),
            $this->getLuckyNumbers($lucky_numbers));

        list($expected, $actual) = $this->exerciseAdd($user, $euroMillionsLine);
        $this->assertEquals($expected, $actual);
    }

    /**
     * method getPlayConfigsByDrawDayAndDate
     * when called
     * should returnProperResult
     * @dataProvider getDateAndExpectedIds
     */
    public function test_getPlayConfigsByDrawDayAndDate_called_returnProperResult($date, $ids)
    {
        $this->markTestSkipped('A lo mejor no tiene sentido en este momento');
        $date_expected = new \DateTime($date);
        $expected = $ids;
        $actual = $this->sut->getPlayConfigsByDrawDayAndDate($date_expected);
        $this->assertEquals($expected, $this->getIdsFromArrayOfObjects($actual));
    }




    /**
     * method getPlayConfigsByUser
     * when called
     * should returnProperResult
     */
    public function test_getPlayConfigsByUser_called_returnProperResult()
    {
        $this->markTestSkipped('A lo mejor no tiene sentido en este momento');
        $date = new \DateTime('2015-09-20 00:00:00');
        $userId = '9098299B-14AC-4124-8DB0-19571EDABE56';
        $expected = [2, 5];
        $actual = $this->sut->getPlayConfigsByUserAndDate($userId, $date);
        $this->assertEquals($expected, $this->getIdsFromArrayOfObjects($actual));
    }


    public function getDateAndExpectedIds()
    {
        return [
            ['2015-10-06', [3, 9]],
            ['2015-09-22', [1, 2]],
            ['2001-09-01', []]
        ];
    }

    /**
     * method getTotalByUserAndPlayForNextDraw
     * when called
     * should returnTotalAmountByUser
     */
    public function test_getTotalByUserAndPlayForNextDraw_called_returnTotalAmountByUser()
    {
        $this->markTestSkipped('A lo mejor no tiene sentido en este momento');
        $userId = '9098299B-14AC-4124-8DB0-19571EDABE56';
        $date = new \DateTime('2015-09-22 00:00:00');
        $actual = $this->sut->getTotalByUserAndPlayForNextDraw($userId, $date);
        $this->assertEquals(2, $actual);
    }


    /**
     * method getPlayConfigsByCollectionIds
     * when calledPassingAValidArrayOfIds
     * should returnCollectionOfPlayConfigs
     */
    public function test_getPlayConfigsByCollectionIds_calledPassingAValidArrayOfIds_returnCollectionOfPlayConfigs()
    {
        $ids = [1, 2, 3];
        $actual = $this->sut->getPlayConfigsByCollectionIds($ids);
        $this->assertEquals(3, count($actual));
        $this->assertEquals(1, $actual[0]->getId());
        $this->assertEquals(2, $actual[1]->getId());
        $this->assertEquals(3, $actual[2]->getId());
    }

    /**
     * method getPlayConfigsByCollectionIds
     * when calledPassingInvalidArrayOfIds
     * should returnEmtpyCollection
     */
    public function test_getPlayConfigsByCollectionIds_calledPassingInvalidArrayOfIds_returnEmtpyCollection()
    {
        $ids = [1000, 10001, 1200];
        $actual = $this->sut->getPlayConfigsByCollectionIds($ids);
        $this->assertEquals(0, count($actual));
    }


    /**
     * method getActivePlayConfigsByUser
     * when called
     * should returnPlayConfigsActiveByUser
     */
    public function test_getActivePlayConfigsByUser_called_returnPlayConfigsActiveByUser()
    {
        $expected=3;
        $userId= '9098299B-14AC-4124-8DB0-19571EDABE56';
        $actual = $this->sut->getActivePlayConfigsByUser($userId);
        $this->assertEquals($expected, count($actual));
    }

    /**
     * method updateToInactives
     * when called
     * should updateMatchingRowsToInactive
     */
    public function test_updateToInactives_called_updateMatchingRowsToInactive()
    {
        $date = new \DateTime('2016-01-01');
        $lottery = LotteryMother::anEuroMillions();
        $actual = $this->sut->updateToInactives($date,$lottery);
        $this->assertEquals(7, $actual);
    }



    private function exerciseAdd($user, $euroMillionsLine)
    {
        $playConfig = new PlayConfig();
        $playConfig->setUser($user);
        $playConfig->setLine($euroMillionsLine);
        $playConfig->setActive(true);
        $this->sut->add($playConfig);
        $this->entityManager->flush();
        $actual = $this->entityManager
            ->createQuery(
                'SELECT p'
                . ' FROM \EuroMillions\web\entities\PlayConfig p'
                . ' WHERE p.user = :user_id ')
            ->setParameters(['user_id' => $user->getId()])
            ->getResult()[0];
        return array($playConfig, $actual);

    }
}