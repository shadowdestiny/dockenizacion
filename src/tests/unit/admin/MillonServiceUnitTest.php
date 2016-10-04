<?php


namespace tests\unit\admin;


use EuroMillions\admin\services\MillonService;
use EuroMillions\shared\config\Namespaces;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\helpers\mothers\UserMother;
use EuroMillions\web\entities\Matcher;
use Money\Currency;
use Money\Money;

class MillonServiceUnitTest extends UnitTestBase
{


    private $matcherRepository_double;

    protected function getEntityManagerStubExtraMappings()
    {
        return [
            Namespaces::ENTITIES_NS . 'Matcher' => $this->matcherRepository_double,
        ];
    }

    public function setUp()
    {
        $this->matcherRepository_double= $this->getRepositoryDouble('MatcherRepository');
        parent::setUp();
    }

    private function getSut()
    {
        return new MillonService($this->getEntityManagerRevealed());
    }


    /**
     * method findWinnerMillion
     * when called
     * should returnArrayWithUsersMillionWinners
     */
    public function test_findWinnerMillion_called_returnArrayWithUsersMillionWinners()
    {
        $dateTime = new \DateTime();
        $matcher = new Matcher();
        $matcher->initialize([
            'id' => 1,
            'matchSide' => '',
            'drawDate' => $dateTime,
            'matchStatus' => '',
            'matchID' => '',
            'matchTypeID' => '',
            'matchDate' => '',
            'user' => UserMother::aJustRegisteredUser()->build(),
            'providerBetId' => '11111',
            'prize' => new Money((int) 0, new Currency('EUR')),
            'type' => 'P',
            'raffleMillion' => 'BNN41949-BNN41949',
            'raffleRain' => ''
        ]);
        $matcherTwo = new Matcher();
        $matcherTwo->initialize([
            'id' => 2,
            'matchSide' => '',
            'drawDate' => $dateTime,
            'matchStatus' => '',
            'matchID' => '',
            'matchTypeID' => '',
            'matchDate' => '',
            'user' => UserMother::aJustRegisteredUser()->build(),
            'providerBetId' => '11112',
            'prize' => new Money((int) 0, new Currency('EUR')),
            'type' => 'P',
            'raffleMillion' => 'BNN41607-BNN41607',
            'raffleRain' => ''
        ]);
        $this->matcherRepository_double->fetchRaffleMillionByDrawDate($dateTime)->willReturn([$matcher,$matcherTwo]);
        $actual = $this->getSut()->findWinnerMillon($dateTime,'BNN41607');
        $this->assertCount(1,$actual);
        $this->assertEquals('nonexisting@email.com',$actual[0]);
    }

}