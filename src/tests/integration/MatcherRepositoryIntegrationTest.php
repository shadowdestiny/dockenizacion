<?php


namespace tests\integration;


use EuroMillions\tests\base\DatabaseIntegrationTestBase;
use EuroMillions\web\repositories\MatcherRepository;

class MatcherRepositoryIntegrationTest extends DatabaseIntegrationTestBase
{

    /** @var  MatcherRepository*/
    protected $sut;

    /**
     * Child classes must implement this method. Return empty array if no fixtures are needed
     * @return array
     */
    protected function getFixtures()
    {
        return [
            'users',
            'matcher',
        ];
    }


    public function setUp()
    {
        parent::setup();
        $this->sut = $this->entityManager->getRepository($this->getEntitiesToArgument('Matcher'));
    }


    /**
     * method fetchRaffleMillionByDrawDate
     * when called
     * should returnArrayWithUsersAndRiffleMillion
     */
    public function test_fetchRaffleMillionByDrawDate_called_returnArrayWithUsersAndRiffleMillion()
    {
        $date = new \DateTime('2016-09-30');
        $actual = $this->sut->fetchRaffleMillionByDrawDate($date);
        $this->assertCount(2,$actual);
    }




}