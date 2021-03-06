<?php


namespace EuroMillions\tests\unit;


use EuroMillions\tests\helpers\mothers\PlayConfigMother;
use EuroMillions\web\services\external_apis\LotteryValidationCastilloApi;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\vo\CastilloBetId;
use EuroMillions\web\vo\CastilloCypherKey;
use EuroMillions\web\vo\CastilloTicketId;
use Prophecy\Argument;
use EuroMillions\tests\base\LotteryValidationCastilloRelatedTest;
use EuroMillions\tests\base\UnitTestBase;
use EuroMillions\tests\unit\utils\CurlResponse;

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
        $this->cypher_double = $this->prophesize($this->getInterfacesToArgument('ICypherStrategy'));
        $this->castilloTicketId_double = $this->prophesize($this->getVOToArgument('CastilloTicketId'));
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
        $this->curlWrapper_double->post('https://www.loteriacastillo.com/test-euromillions')->shouldBeCalled();
        $this->curlWrapper_double->setOption(CURLOPT_SSL_VERIFYPEER,0)->shouldBeCalled();
        $this->curlWrapper_double->setOption(CURLOPT_POSTFIELDS,$xml)->shouldBeCalled();
        $this->curlWrapper_double->setOption(CURLOPT_RETURNTRANSFER,1)->shouldBeCalled();
        $this->curlWrapper_double->setOption(CURLOPT_POST,1)->shouldBeCalled();
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
        $expected = new ActionResult(true,'');
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
    public function test_validateBet_called_withProperXmlAndContentTicketDynamic()
    {
        $bet = $this->getBetForValidation();
        $castilloCypherKey = CastilloCypherKey::create();
        $this->castilloTicketId_double->create()->willReturn(null);
        $this->castilloTicketId_double->id()->willReturn('123456');
        $content = "<?xml version='1.0' encoding='UTF-8'?><ticket type='6' date='151004' bets='1' price='2.50'><id>123456</id><combination><number>7</number><number>15</number><number>16</number><number>17</number><number>22</number><star>1</star><star>7</star></combination></ticket>";
        $this->cypher_double->encrypt($castilloCypherKey->key(), $content)->willReturn('content cifrado');
        $this->cypher_double->getSignature('content cifrado')->willReturn('signature cifrada');
        $this->curlWrapper_double->post('https://www.loteriacastillo.com/test-euromillions')->willReturn(new CurlResponse(self::$xml_with_fake_cyphered_content));
        $this->cypher_double->decrypt('Esto es contenido cifrado', '6')->willReturn(self::$content_with_ok_result);
        $xml = '<?xml version="1.0" encoding="UTF-8"?><message><operation id="'.$bet->getCastilloBet()->id().'" key="'.$castilloCypherKey->key().'" type="1"><content>content cifrado</content></operation><signature>signature cifrada</signature></message>';
        $this->curlWrapper_double->post('https://www.loteriacastillo.com/test-euromillions')->shouldBeCalled();
        $this->curlWrapper_double->setOption(CURLOPT_SSL_VERIFYPEER,0)->shouldBeCalled();
        $this->curlWrapper_double->setOption(CURLOPT_POSTFIELDS,$xml)->shouldBeCalled();
        $this->curlWrapper_double->setOption(CURLOPT_RETURNTRANSFER,1)->shouldBeCalled();
        $this->curlWrapper_double->setOption(CURLOPT_POST,1)->shouldBeCalled();
        $this->exerciseValidate($bet,$castilloCypherKey);
    }

    /**
     * method validateBetInGroup
     * when called
     * should callProperUrlWithProperXmlForGroupBets
     */
    public function test_validateBetInGroup_called_callProperUrlWithProperXmlForGroupBets()
    {
        $playConfigOne = PlayConfigMother::aPlayConfig()->build();
        $playConfigTwo = PlayConfigMother::aPlayConfig()->build();
        list($bet, $castilloCypherKey) = $this->prepareForSendingGroupValidation(self::$content_with_ok_result);
        $xml = '<?xml version="1.0" encoding="UTF-8"?><message><operation id="123456" key="'.$castilloCypherKey->key().'" type="1"><content>content cifrado</content></operation><signature>signature cifrada</signature></message>';
        $this->curlWrapper_double->post('https://www.loteriacastillo.com/test-euromillions')->shouldBeCalled();
        $this->curlWrapper_double->setOption(CURLOPT_SSL_VERIFYPEER,0)->shouldBeCalled();
        $this->curlWrapper_double->setOption(CURLOPT_POSTFIELDS,$xml)->shouldBeCalled();
        $this->curlWrapper_double->setOption(CURLOPT_RETURNTRANSFER,1)->shouldBeCalled();
        $this->curlWrapper_double->setOption(CURLOPT_POST,1)->shouldBeCalled();
        $this->exerciseValidateBetInGroup($castilloCypherKey,[$playConfigOne,$playConfigTwo]);
    }



    private function getSut()
    {
        return new LotteryValidationCastilloApi($this->curlWrapper_double->reveal());
    }


    /**
     * @param $bet
     * @param $castilloCypherKey
     * @return ActionResult
     */
    private function exerciseValidate($bet, $castilloCypherKey)
    {
        $sut = $this->getSut();
        $date_ticket = new \DateTime('2015-10-04');
        $cypher_method = $this->cypher_double->reveal();
        $castillo_id = $this->castilloTicketId_double->reveal();
        $actual = $sut->validateBet($bet, $cypher_method, $castilloCypherKey,$castillo_id,$date_ticket, $bet->getPlayConfig()->getLine());
        return $actual;
    }


    private function exerciseValidateBetInGroup($castilloCypherKey,$playConfigsArrayInGroup)
    {
        $sut = $this->getSut();
        $date_ticket = new \DateTime('2015-10-04');
        $cypher_method = $this->cypher_double->reveal();
        $castillo_id = $this->castilloTicketId_double->reveal();
        $actual = $sut->validateBetInGroup($cypher_method,$date_ticket,$playConfigsArrayInGroup,$castilloCypherKey,$castillo_id);
        return $actual;
    }

    private function prepareCurl()
    {
        $this->curlWrapper_double->setOption(CURLOPT_POSTFIELDS, Argument::type('string'))->willReturn(null);
        $this->curlWrapper_double->setOption(CURLOPT_SSL_VERIFYPEER, 0)->willReturn(null);
        $this->curlWrapper_double->setOption(CURLOPT_RETURNTRANSFER,1)->willReturn(null);
        $this->curlWrapper_double->setOption(CURLOPT_POST,1)->willReturn(null);
    }

    /**
     * @param $contentResult
     * @return array
     */
    private function prepareForSendingValidation($contentResult)
    {
        $bet = $this->getBetForValidation();
        $castilloCypherKey = CastilloCypherKey::create();
        $this->castilloTicketId_double->create()->willReturn(null);
        $this->castilloTicketId_double->id()->willReturn('123456');
        $this->cypher_double->encrypt($castilloCypherKey->key(), $this->getXmlContent())->willReturn('content cifrado');
        $this->cypher_double->getSignature('content cifrado')->willReturn('signature cifrada');
        $this->curlWrapper_double->post('https://www.loteriacastillo.com/test-euromillions')->willReturn(new CurlResponse(self::$xml_with_fake_cyphered_content));
        $this->cypher_double->decrypt('Esto es contenido cifrado', '6')->willReturn($contentResult);
        return array($bet, $castilloCypherKey);
    }


    /**
     * @param $contentResult
     * @return array
     */
    private function prepareForSendingGroupValidation($contentResult)
    {
        $bet = $this->getBetForValidation();
        $castilloCypherKey = CastilloCypherKey::create();
        $this->castilloTicketId_double->create()->willReturn(null);
        $this->castilloTicketId_double->id()->willReturn('123456');
        $this->castilloTicketId_double->id()->willReturn('123456');
        $this->cypher_double->encrypt($castilloCypherKey->key(), $this->getXmlContentGrouped())->willReturn('content cifrado');
        $this->cypher_double->getSignature('content cifrado')->willReturn('signature cifrada');
        $this->curlWrapper_double->post('https://www.loteriacastillo.com/test-euromillions')->willReturn(new CurlResponse(self::$xml_with_fake_cyphered_content));
        $this->cypher_double->decrypt('Esto es contenido cifrado', '6')->willReturn($contentResult);
        return array($bet, $castilloCypherKey);
    }


}