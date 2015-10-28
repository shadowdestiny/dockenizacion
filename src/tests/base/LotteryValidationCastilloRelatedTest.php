<?php


namespace tests\base;


use EuroMillions\web\entities\Bet;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\vo\CastilloBetId;
use EuroMillions\web\vo\EuroMillionsLine;
use EuroMillions\web\vo\EuroMillionsLuckyNumber;
use EuroMillions\web\vo\EuroMillionsRegularNumber;

trait LotteryValidationCastilloRelatedTest
{

    protected static $timestamp = '20151022084113328056';
    protected static $content_with_ok_result = '<?xml version="1.0" encoding="UTF-8"?><acknowledge><id>[ID]</id><status>OK</status><message>Ticket correctly received.</message><drawdate>2016-10-04</drawdate></acknowledge>';
    protected static $content_with_ko_result = '<?xml version="1.0" encoding="UTF-8"?><acknowledge><id>20151022084113328056</id><status>KO</status><message>Esto es un message de error</message><drawdate></drawdate></acknowledge>';
    protected static $cyphered_signature = 'NmRmNTE0MzMwNTQ3YjlmMThlMmYzODZmMGIxMDJkZjY5NTNlZGY0ZQ==';
    protected static $cyphered_content = 'i3FPjVa4VO+6RaCr6q09Hl/eg2FjH9czv622SaSaZS8BkLOkRojN3ejSxDJ+N7Fhh4fKw43hT+EgFoiZqricbt+LWfHw1Z186uz/OGyULb2KmezdRU8XCK8YJUbwOO9L6jzjFFsr9GQLSZZ2PDTJi5b7uVR/MGjTJexNPmJTZ3G/AO+aZpphBJoKlgiqgHJFB+zvczYgzJjO1jUy1QKed87NuKuJhTq26hQSwfmIR87MY22SU6v9bZKw4WeuxCBo2fiGw1tT6RP2OpHn+FFXCD02M0tygQfsLifCsCWJyI3U9FoEu4pR+iotosu3VlXqy/nBIhpqA1poeB5ijuTH2g762HWlMqY0ygKp2xtVPqY=';
    protected static $xml_with_fake_cyphered_content = '<?xml version="1.0" encoding="UTF-8"?><message><operation id="20151023105043897249" key="6" type="6"><content>Esto es contenido cifrado</content></operation><signature>ZTliNzNkY2U2M2FmODJmNjJiMGUyMWJhZWJjYmYyOGYwYzc5M2RmZQ==</signature></message>';
    protected static $key = 1;
    protected static $xml_ko_uncypeherd = '<?xml version="1.0" encoding="UTF-8"?><message><operation id="20151023112721719110" key="7" type="6"><content><?xml version="1.0" encoding="UTF-8"?><acknowledge><id>20151022084113328056</id><status>KO</status><message>Esto es un message de error</message><drawdate></drawdate></acknowledge></content></operation><signature>YzY1NDM3ZDg3ZmQ4MWMzMWU2Nzc1ZjZlYTRiNjhjNzY4NWI3OWY1Nw==</signature></message>';

    protected function getCurlWrapperWithXmlRequest()
    {
        return $this->setCurlWrapper($this->xml_request);
    }

    /**
     * @param $rss
     * @return \Phalcon\Http\Client\Provider\Curl|\PHPUnit_Framework_MockObject_MockObject
     */
    private function setCurlWrapper($xml_request)
    {
        $response = new \stdClass();
        $response->body = $xml_request;

        /** @var \Phalcon\Http\Client\Provider\Curl|\PHPUnit_Framework_MockObject_MockObject $curlWrapper_stub */
        $curlWrapper_stub =
            $this->getMockBuilder(
                '\Phalcon\Http\Client\Provider\Curl'
            )->getMock();
        $curlWrapper_stub->expects($this->any())
            ->method('get')
            ->will($this->returnValue($response));
        return $curlWrapper_stub;
    }

    protected function getXmlContent()
    {
        return  $content = "<?xml version='1.0' encoding='UTF-8'?><ticket type='6' date='151004' bets='1' price='2'><id>123456</id><combination><number>7</number><number>15</number><number>16</number><number>17</number><number>22</number><star>1</star><star>7</star></combination></ticket>";
    }

    protected function getPlayConfig()
    {
        $reg = [7,16,17,22,15];
        $regular_numbers = [];
        foreach($reg as $regular_number){
            $regular_numbers[] = new EuroMillionsRegularNumber($regular_number);
        }
        $luck = [7,1];
        $lucky_numbers = [];
        foreach($luck as $lucky_number){
            $lucky_numbers[] = new EuroMillionsLuckyNumber($lucky_number);
        }

        $playConfig = new PlayConfig();
        $line = new EuroMillionsLine($regular_numbers,$lucky_numbers);
        $playConfig->setLine($line);
        return $playConfig;
    }

    /**
     * @return Bet
     */
    protected function getBetForValidation()
    {
        $play_config = $this->getPlayConfig();
        $euromillions_draw = new EuroMillionsDraw();
        $bet_id_castillo = CastilloBetId::create();
        $bet = new Bet($play_config, $euromillions_draw);
        $bet->setCastilloBet($bet_id_castillo);
        return $bet;
    }

}