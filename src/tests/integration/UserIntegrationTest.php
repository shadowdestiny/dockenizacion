<?php


namespace tests\integration;


use EuroMillions\tests\base\DatabaseIntegrationTestBase;
use EuroMillions\web\repositories\UserRepository;

class UserIntegrationTest extends DatabaseIntegrationTestBase
{

    /** @var UserRepository */
    protected $userRespository;

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
        $this->userRespository = $this->entityManager->getRepository($this->getEntitiesToArgument('User'));
    }


    /**
     * method getPlayConfigsFilteredForNextDraw
     * when called
     * should returnProperData
     */
    public function test_getPlayConfigsFilteredForNextDraw_called_returnProperData()
    {
        $date = new \DateTime('2015-09-22');
        $users = $this->userRespository->getUsersWithPlayConfigsOrderByLottery();
        $this->assertEquals(1,count($users[0]->getPlayConfigsFilteredForNextDraw($date)));
        $this->assertEquals(2,count($users[1]->getPlayConfigsFilteredForNextDraw($date)));
    }

    /**
     * method getPlayConfigsFilteredForNextDraw
     * when called
     * should returnEmptyResult
     */
    public function test_getPlayConfigsFilteredForNextDraw_called_returnEmptyResult()
    {
        $date = new \DateTime('2015-08-22');
        $users = $this->userRespository->getUsersWithPlayConfigsOrderByLottery();
        $this->assertEmpty($users[0]->getPlayConfigsFilteredForNextDraw($date));
    }
    
}