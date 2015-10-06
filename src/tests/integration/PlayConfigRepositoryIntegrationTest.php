<?php


namespace tests\integration;


use EuroMillions\entities\PlayConfig;
use EuroMillions\repositories\PlayConfigRepository;
use EuroMillions\vo\DrawDays;
use EuroMillions\vo\EuroMillionsLine;
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
            'users'
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
        $user = $this->entityManager->find('EuroMillions\entities\User', '9098299B-14AC-4124-8DB0-19571EDABE55');
        $regular_numbers = [1, 2, 3, 4, 5];
        $lucky_numbers = [5, 8];
        $euroMillionsLine = new EuroMillionsLine($this->getRegularNumbers($regular_numbers),
            $this->getLuckyNumbers($lucky_numbers));

        list($expected, $actual) = $this->exerciseAdd($user, $euroMillionsLine);
        $this->assertEquals($expected, $actual);
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
            ->setMaxResults(1)
            ->setParameters(['user_id' => $user->getId() ])
            ->getResult()[0];
        return array($playConfig,$actual);

    }
}