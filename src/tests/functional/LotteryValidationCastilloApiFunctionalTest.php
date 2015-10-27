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
use EuroMillions\vo\CastilloTicketId;
use tests\base\DatabaseIntegrationTestBase;
use tests\base\LotteryValidationCastilloRelatedTest;

class LotteryValidationCastilloApiFunctionalTest extends DatabaseIntegrationTestBase
{

    use LotteryValidationCastilloRelatedTest;

    private $id_for_test;

    private $cypher_double;

    private $id_ticket_for_test;



    public function setUp()
    {
        if (empty($this->id_for_test)) {
            $this->id_for_test = CastilloBetId::create();
        }
        if(empty($this->id_ticket_for_test)) {
            $this->id_ticket_for_test = CastilloTicketId::create();
        }
        $this->cypher_double = $this->prophesize('EuroMillions\interfaces\ICypherStrategy');
        parent::setUp();
    }


    /**
     * method validateBet
     * when calledWithNotUsedId
     * should returnActionResultTrue
     */
    public function test_validateBet_calledWithNotUsedId_returnActionResultTrue()
    {
        $actual = $this->exerciseValidation($this->id_ticket_for_test,new \DateTime('2016-10-04'));
        $expected = new ActionResult(true);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method validateBet
     * when calledWithTicketUsed
     * should returnActionResultFalse
     */
    public function test_validateBet_calledWithTicketUsed_returnActionResultFalse()
    {
        $actual = $this->exerciseValidation(new CastilloTicketId('2176681082'), new \DateTime('2016-10-04'));
        $expected = new ActionResult(false,'Ticket id (2176681082) already received.');
        $this->assertEquals($expected,$actual);

    }

    /**
     * method validateBet
     * when calledWithIncorrectDate
     * should returnActionResultFalse
     */
    public function test_validateBet_calledWithIncorrectDate_returnActionResultFalse()
    {
        $actual = $this->exerciseValidation($this->id_ticket_for_test, new \DateTime('2015-10-04'));
        $expected = new ActionResult(false,'Invalid ticket date.');
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
        $actual = $sut->validateBet($bet,$cypher,null,null,new \DateTime('2016-10-04'));
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

    /**
     * @return ActionResult
     */
    protected function exerciseValidation($id_ticket_for_test, $date_time = null)
    {
        $bet = $this->getBetForValidation();
        $castilloCypherKey = CastilloCypherKey::create();
        $bet->setCastilloBet($this->id_for_test);
        $cypher = new CypherCastillo3DES();
        $sut = $this->getSut();
        $actual = $sut->validateBet($bet, $cypher, $castilloCypherKey, $id_ticket_for_test,$date_time);
        return $actual;
    }

    //EMTD date should be a draw date valid
}