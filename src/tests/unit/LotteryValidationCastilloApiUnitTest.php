<?php


namespace tests\unit;


use EuroMillions\components\CypherCastillo3DES;
use EuroMillions\entities\Bet;
use EuroMillions\entities\EuroMillionsDraw;
use EuroMillions\NullCypher;
use EuroMillions\services\external_apis\LotteryValidationCastilloApi;
use EuroMillions\vo\ActionResult;
use EuroMillions\vo\CastilloBetId;
use EuroMillions\vo\CastilloCypherKey;
use EuroMillions\vo\CastilloTicketId;
use Prophecy\Argument;
use tests\base\LotteryValidationCastilloRelatedTest;
use tests\base\UnitTestBase;
use tests\unit\utils\CurlResponse;

class LotteryValidationCastilloApiUnitTest extends UnitTestBase
{

    use LotteryValidationCastilloRelatedTest;

    private $curlWrapper_double;

    private $cypher_double;

    private $castilloTicketId_double;

    public function setUp()
    {
        parent::setUp();
        $this->curlWrapper_double = $this->prophesize('\Phalcon\Http\Client\Provider\Curl');
        $this->cypher_double = $this->prophesize('EuroMillions\interfaces\ICypherStrategy');
        $this->castilloTicketId_double = $this->prophesize('EuroMillions\vo\CastilloTicketId');
    }


    /**
     * method validateBet
     * when called
     * should callProperUrlWithProperXml
     */
    public function test_validateBet_called_callProperUrlWithProperXml()
    {
        list($bet, $castilloCypherKey) = $this->prepareForSendingValidation(self::$content_with_ok_result);

        $xml = '<?xml version="1.0" encoding="UTF-8"?><message><operation id="'.$bet->getCastilloBet()->id().'" key="'.$castilloCypherKey->key().'" type="1"><content>content cifrado</content></operation><signature>signature cifrada</signature></message>';
        $this->curlWrapper_double->post('https://www.loteriacastillo.com/euromillions/')->shouldBeCalled();
        $this->curlWrapper_double->setOption(CURLOPT_SSL_VERIFYPEER,0)->shouldBeCalled();
        $this->curlWrapper_double->setOption(CURLOPT_POSTFIELDS,$xml)->shouldBeCalled();
        $this->exerciseValidate($bet,$castilloCypherKey);
    }


    /**
     * method validateBet
     * when apiReturnsKO
     * should returnActionResultWithFalseAndErrorMessage
     */
    public function test_validateBet_apiReturnsKO_returnActionResultWithFalseAndErrorMessage()
    {
        list($bet, $castilloCypherKey) = $this->prepareForSendingValidation(self::$content_with_ko_result);

        $this->prepareCurl();
        $actual = $this->exerciseValidate($bet,$castilloCypherKey);
        $expected = new ActionResult(false,'Esto es un message de error');
        $this->assertEquals($expected,$actual);
    }


    /**
     * method validateBet
     * when apiReturnOK
     * should returnActionResultTrue
     */
    public function test_validateBet_apiReturnOK_returnActionResultTrue()
    {
        list($bet, $castilloCypherKey) = $this->prepareForSendingValidation(self::$content_with_ok_result);
        $this->prepareCurl();

        $actual = $this->exerciseValidate($bet,$castilloCypherKey);
        $expected = new ActionResult(true);
        $this->assertEquals($expected,$actual);
    }

    /**
     * method validateBet
     * when called
     * should withProperXmlAndContentTicketDynamic
     */
/*    public function test_validateBet_called_withProperXmlAndContentTicketDynamic()
    {
        $bet = $this->getBetForValidation();
        $castilloCypherKey = CastilloCypherKey::create();
        $date_ticket = new \DateTime();
        $this->castilloTicketId_double->create()->willReturn(null);
        $this->castilloTicketId_double->id()->willReturn('123456');
        $content = "<?xml version='1.0' encoding='UTF-8'?><ticket type='6' date='".$date_ticket->format('ymd')."' bets='1' price='2'><id>123456</id><combination><number>7</number><number>15</number><number>16</number><number>17</number><number>22</number><star>1</star><star>7</star></combination></ticket>";
        $this->cypher_double->encrypt($castilloCypherKey->key(), $content)->willReturn('content cifrado');
        $this->cypher_double->getSignature('content cifrado')->willReturn('signature cifrada');
        $this->curlWrapper_double->post('https://www.loteriacastillo.com/euromillions/')->willReturn(new CurlResponse(self::$xml_with_fake_cyphered_content));
        $this->cypher_double->decrypt('Esto es contenido cifrado', '6')->willReturn(self::$content_with_ok_result);

        $xml = '<?xml version="1.0" encoding="UTF-8"?><message><operation id="'.$bet->getCastilloBet()->id().'" key="'.$castilloCypherKey->key().'" type="1"><content>content cifrado</content></operation><signature>signature cifrada</signature></message>';
        $this->curlWrapper_double->post('https://www.loteriacastillo.com/euromillions/')->shouldBeCalled();
        $this->curlWrapper_double->setOption(CURLOPT_SSL_VERIFYPEER,0)->shouldBeCalled();
        $this->curlWrapper_double->setOption(CURLOPT_POSTFIELDS,$xml)->shouldBeCalled();
        $this->exerciseValidate($bet,$castilloCypherKey);

    }*/



    
    private function getSut()
    {
        return new LotteryValidationCastilloApi($this->curlWrapper_double->reveal());
    }



    /**
     * @param $bet
     * @param $castilloCypherKey
     */
    private function exerciseValidate($bet, $castilloCypherKey)
    {
        $sut = $this->getSut();
        $cypher_method = $this->cypher_double->reveal();
        $castillo_id = $this->castilloTicketId_double->reveal();
        $actual = $sut->validateBet($bet, $cypher_method, $castilloCypherKey,$castillo_id);
        return $actual;
    }

    private function prepareCurl()
    {
        $this->curlWrapper_double->setOption(CURLOPT_POSTFIELDS, Argument::type('string'))->willReturn(null);
        $this->curlWrapper_double->setOption(CURLOPT_SSL_VERIFYPEER, 0)->willReturn(null);
    }

    /**
     * @return array
     */
    private function prepareForSendingValidation($contentResult)
    {
        $bet = $this->getBetForValidation();
        $castilloCypherKey = CastilloCypherKey::create();
        $this->cypher_double->encrypt($castilloCypherKey->key(), $this->getXmlContent())->willReturn('content cifrado');
        $this->cypher_double->getSignature('content cifrado')->willReturn('signature cifrada');
        $this->curlWrapper_double->post('https://www.loteriacastillo.com/euromillions/')->willReturn(new CurlResponse(self::$xml_with_fake_cyphered_content));
        $this->cypher_double->decrypt('Esto es contenido cifrado', '6')->willReturn($contentResult);
        return array($bet, $castilloCypherKey);
    }


    //EMTD crear test unitario asegurar que lo que se la pasa al encrypt es correcto dependiendo parametros de entrada(date, id, type = 6) el xml sea correcto. Quizas implementar generador de IDS
    //EMTD unit test generador de ids y simularlo en el test para que devuelva el id requerido.
    //EMTD Funcional para comprobar que sigue funcionando. Primer test con un id session que nunca se ha utilizado. Segundo test utilizar un id ya existente para que devuelva ko.
    //EMTD ADVICE: step by step


}