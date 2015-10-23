<?php


namespace tests\functional;


use EuroMillions\components\CypherCastillo3DES;
use EuroMillions\entities\Bet;
use EuroMillions\entities\EuroMillionsDraw;
use EuroMillions\entities\PlayConfig;
use EuroMillions\services\external_apis\LotteryValidationCastilloApi;
use EuroMillions\vo\ActionResult;
use EuroMillions\vo\CastilloBetId;
use EuroMillions\vo\CastilloCypherKey;
use tests\base\DatabaseIntegrationTestBase;
use tests\base\LotteryValidationCastilloRelatedTest;

class LotteryValidationCastilloApiFunctionalTest extends DatabaseIntegrationTestBase
{

    use LotteryValidationCastilloRelatedTest;

    private $id_for_test;

    private $cypher_double;

    public function setUp()
    {
        parent::setUp();
        if (empty($this->id_for_test)) {
            $this->id_for_test = CastilloBetId::create();
        }
        $this->cypher_double = $this->prophesize('EuroMillions\interfaces\ICypherStrategy');
    }


    /**
     * method validateBet
     * when calledWithNotUsedId
     * should returnActionResultTrue
     */
    public function test_validateBet_calledWithNotUsedId_returnActionResultTrue()
    {
        $bet = $this->getBetForValidation();
        $castilloCypherKey = CastilloCypherKey::create();
        $bet->setCastilloBet($this->id_for_test);
        $cypher = new CypherCastillo3DES();
        $sut = $this->getSut();
        $actual = $sut->validateBet($bet,$cypher,$castilloCypherKey);
        $expected = new ActionResult(true);
        $this->assertEquals($expected,$actual);
    }


    /**
     * method validateBet
     * when calledWithNotUsedId
     * should returnOkResult
     */
    public function test_validateBet_calledWithNotUsedId_returnOkResult()
    {
        $id_session = $this->id_for_test;
        //$ok_result = str_replace('[ID]', $id_session, self::$ok_result));
        $expected = new ActionResult(true);
        $play_config = $this->getPlayConfig();
        $euromillions_draw = new EuroMillionsDraw();
        $cypher = new CypherCastillo3DES();
        $bet = new Bet($play_config,$euromillions_draw);
        $bet->setCastilloBet($id_session);
        $sut = $this->getSut();
        $actual = $sut->validateBet($bet,$cypher);
        print_r($actual);
        $this->assertEquals($expected, $actual);
    }



    /**
     * method validateBet
     * when calledWithUsedId
     * should returnKOResult
     */
    public function test_validateBet_calledWithUsedId_returnKOResult()
    {

    }

    public function getSut()
    {
        return new LotteryValidationCastilloApi();
    }

    /**
     * Child classes must implement this method. Return empty array if no fixtures are needed
     * @return array
     */
    protected function getFixtures()
    {
        return [];
    }
}