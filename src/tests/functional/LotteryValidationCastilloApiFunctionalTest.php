<?php


namespace tests\functional;


use EuroMillions\services\external_apis\LotteryValidationCastilloApi;
use Phalcon\Http\Client\Provider\Curl;
use tests\base\DatabaseIntegrationTestBase;

class LotteryValidationCastilloApiFunctionalTest extends DatabaseIntegrationTestBase
{

    /**
     * method validateBet
     * when called
     * should returnAcceptableResult
     */
    public function test_validateBet_called_returnAcceptableResult()
    {
        //        $sut = new LotteryValidationCastilloApi();
        //        $actual = $sut->request()

        $cypher = \mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_CBC, '');

        $content = <<< 'EOD'
<?xml version="1.0" encoding="UTF-8"?>
<ticket type="1" date="100422" bets="1" price="1.00">
<id>2330000001</id>
<combination>
Antonio Navarro Navarro
C/Paz, 7, 9. 46003 - Valencia
http://www.ticsconsulting.es
anavarro@ticsconsulting.es
Loteria Castillo – Euromillions XML interface – v1.10 – September 6th 2013 – Page 5
Consultoría & Seguridad
<number>3</number>
<number>12</number>
<number>24</number>
<number>27</number>
<number>30</number>
<number>35</number>
</combination>
</ticket>;
EOD;

        $content_cyphered = base64_encode($this->encrypt($cypher,self::$cypher_keys[0],$content));
        $signature = sha1(base64_decode($content_cyphered).'1234567890');
        $xml = <<< EOD
<?xml version="1.0" encoding="UTF-8"?>
<message>
 <operation id="123458908809" type="1" key="0">
 <content>
 {$content_cyphered}
 </content>
</operation>
<signature>
{$signature}
</signature>
</message>
EOD;

//        $curl = new Curl();
//        $curl->setOption(CURLOPT_SSL_VERIFYHOST,0);
//        $curl->setOption(CURLOPT_SSL_VERIFYPEER,0);
//        print_r($xml);die();
//        $result = $curl->post('https://www.loteriacastillo.com/euromillions',[
//            'xml' => $xml
//        ]);
//
//        $xml_response  = simplexml_load_string($result->body);
//        $content = (string) $xml_response->operation->content;
//        $key = (int) $xml_response->operation->attributes()['key'];
        $content = strtoupper(bin2hex(base64_decode('gd9xZ4GIEL5z9nTkWDAjoouGOSNLKBjDt4TEFJgOHdKPuNTVnvIPRAhVPEF6fk7vammpRPXT41TKVpXNyc4UEfDyTKzcjXy9qKOyVbtc5B5d4a+uwsRsJDq8ONgap5kGglGDVODsdQMoYTPpUBn1t/3AMW6KaVoD4jivz5br9mLd+YvWgMQv2X+LkfxKP6I+99xvFKuB2HKjoq/7axaoZQ==')));
        var_dump($content);
        $decrypt_result = $this->decryptString($content,self::$cypher_keys[3]);
        $data = 'gd9xZ4GIEL5z9nTkWDAjoouGOSNLKBjDt4TEFJgOHdKPuNTVnvIPRAhVPEF6fk7vammpRPXT41TKVpXNyc4UEfDyTKzcjXy9qKOyVbtc5B5d4a+uwsRsJDq8ONgap5kGglGDVODsdQMoYTPpUBn1t/3AMW6KaVoD4jivz5br9mLd+YvWgMQv2X+LkfxKP6I+99xvFKuB2HKjoq/7axaoZQ==';

      //  $input = base64_decode('GyRCQjZCPjhsOEBCLDtuGyhCIC0gdGVzdGluZw==');//$BB6B>8l8@B,;n(B - testing
        $input_encoding = 'UTF-8';
        echo mb_detect_encoding($decrypt_result);
        //echo iconv('UTF-8', 'UTF-8', $decrypt_result);

        if ( base64_encode(base64_decode($data, true)) === $data){
            echo '$data is valid';
        } else {
            echo '$data is NOT valid';
        }
    }


    private static $cypher_keys = [
        '000000000000000000000000',
        '000000000000000000000001',
        '000000000000000000000002',
        '000000000000000000000003',
        '000000000000000000000004',
        '000000000000000000000005',
        '000000000000000000000006',
        '000000000000000000000007',
        '000000000000000000000008',
        '000000000000000000000009'
    ];

    private function encrypt($cypher, $key, $clear)
    {
        $bs = mcrypt_enc_get_block_size($cypher);

        if ((strlen($clear) % $bs) > 0) {
            $fill = str_repeat(chr(0),8-(strlen($clear) % $bs));
        } else {
            $fill = str_repeat(chr(0),8);
        }
        $clear .= $fill;
        $padding = str_repeat(chr(8),8);
        $iv = str_repeat(chr(0),mcrypt_enc_get_iv_size($cypher));
        mcrypt_generic_init($cypher, $key, $iv);
        $encrypted_data = mcrypt_generic($cypher, $clear.$padding);
        $cyphered = strtoupper(bin2hex($encrypted_data));
        mcrypt_generic_deinit($cypher);
        //close module mcrypt
        mcrypt_module_close($cypher);

        return $cyphered;
    }

    private function decryptString($cyphered, $key) {
        $key = pack("H" . strlen($key), $key);
        $cyphered = pack("H" . strlen($cyphered), $cyphered);
        $td = mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_CBC, '');
        $iv = str_repeat(chr(0),mcrypt_enc_get_iv_size($td));
        mcrypt_generic_init($td, $key, $iv);
        $clear_data = mdecrypt_generic($td, $cyphered);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $clear_data = str_replace(str_repeat(chr(8),8),'',$clear_data);
        $clear_data = str_replace(chr(0),'',$clear_data);

        return($clear_data);
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