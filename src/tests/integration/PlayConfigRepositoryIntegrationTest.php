<?php


namespace tests\integration;


use EuroMillions\entities\PlayConfig;
use EuroMillions\repositories\PlayConfigRepository;
use EuroMillions\vo\DrawDays;
use EuroMillions\vo\EuroMillionsLine;
use EuroMillions\vo\UserId;
use tests\base\DatabaseIntegrationTestBase;
use tests\base\EuroMillionsResultRelatedTest;

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
            'users',
            'play_configs'
        ];
    }

    public function setUp()
    {
        parent::setUp();
        $this->sut = $this->entityManager->getRepository('\EuroMillions\entities\PlayConfig');
    }

    /**
     * method add
     * when called
     * should storeCorrectlyInDatabase
     */
    public function test_add_called_storeCorrectlyInDatabase()
    {
        $user = $this->entityManager->find('EuroMillions\entities\User', '9098299B-14AC-4124-8DB0-19571EDABE57');
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
        $date_expected = new \DateTime($date);
        $expected = $ids;
        $actual = $this->sut->getPlayConfigsByDrawDayAndDate($date_expected);
        $this->assertEquals($expected,$this->getIdsFromArrayOfObjects($actual));
    }

    /**
     * method getPlayConfigsByUser
     * when called
     * should returnProperResult
     */
    public function test_getPlayConfigsByUser_called_returnProperResult()
    {
        $date = new \DateTime('2015-09-20 00:00:00');
        $userId = new UserId('9098299B-14AC-4124-8DB0-19571EDABE56');
        $expected = [2];
        $actual = $this->sut->getPlayConfigsByUserAndDate($userId,$date);
        $this->assertEquals($expected,$this->getIdsFromArrayOfObjects($actual));

    }


    public function getDateAndExpectedIds()
    {
        return [
            ['2015-10-05',[3]],
            ['2015-09-22',[1,2]],
            ['2001-09-01',[]]
        ];
    }

    private function exerciseAdd($user,$euroMillionsLine)
    {
        $playConfig = new PlayConfig();
        $playConfig->setUser($user);
        $playConfig->setLine($euroMillionsLine);
        $playConfig->setActive(true);
        $playConfig->setDrawDays(new DrawDays(2));
        $this->sut->add($playConfig);
        $this->entityManager->flush();
        $actual = $this->entityManager
            ->createQuery(
                'SELECT p'
                . ' FROM \EuroMillions\entities\PlayConfig p'
                . ' WHERE p.user = :user_id ')
            ->setParameters(['user_id' => $user->getId() ])
            ->getResult()[0];
        return array($playConfig,$actual);

    }
}