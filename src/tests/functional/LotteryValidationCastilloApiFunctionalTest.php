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

        $cypher = \mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_CBC, '');

        $content = <<< 'EOD'
<?xml version='1.0' encoding='UTF-8'?>
<ticket type='6' date='161004' bets='1' price='2'>
<id>2330000001</id>
<combination>
<number>7</number>
<number>16</number>
<number>17</number>
<number>22</number>
<number>15</number>
<star>7</star>
<star>1</star>
</combination>
</ticket>
EOD;

        $key = rand(0,9);
        $idsession = strtotime('now');
        $content_cyphered = base64_encode($this->encrypt($cypher,self::$cypher_keys[$key],$content));
        $preshared = '1234567890';
        $signature = sha1(base64_decode($content_cyphered).$preshared);
        $signature = base64_encode($signature);

        $xml = <<< EOD
<?xml version="1.0" encoding="UTF-8"?><message><operation id="'.$idsession.'" key="'.$key.'" type="1">
<content>'.$content_cyphered.'</content></operation>';
<signature>'.$signature.'</signature></message>

EOD;

        $curl = new Curl();
        $curl->setOption(CURLOPT_POST, 1);
        $curl->setOption(CURLOPT_SSL_VERIFYHOST,0);
        $curl->setOption(CURLOPT_SSL_VERIFYPEER,0);
        $curl->setOption(CURLOPT_RETURNTRANSFER,1);
        $curl->setOption(CURLOPT_POSTFIELDS,$xml);

        $result = $curl->post('https://www.loteriacastillo.com/euromillions/');

        $xml_response  = simplexml_load_string($result->body);
        $content = (string) $xml_response->operation->content;
        $key = (int) $xml_response->operation->attributes()['key'];

        $decrypt_result = $this->decryptString(base64_decode($content),self::$cypher_keys[$key]);

        echo $decrypt_result;
    }


    private static $cypher_keys = [
        '000000000000000000000000000000000000000000000000',
        '000000000000000000000000000000000000000000000001',
        '000000000000000000000000000000000000000000000002',
        '000000000000000000000000000000000000000000000003',
        '000000000000000000000000000000000000000000000004',
        '000000000000000000000000000000000000000000000005',
        '000000000000000000000000000000000000000000000006',
        '000000000000000000000000000000000000000000000007',
        '000000000000000000000000000000000000000000000008',
        '000000000000000000000000000000000000000000000009',
    ];

    private function encrypt($cypher, $key, $clear)
    {
        $key = pack("H" . strlen($key), $key);
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

        if ($key && $cyphered) {
            $td = mcrypt_module_open(MCRYPT_3DES, '', 'cbc', '');
            $iv = str_repeat(chr(0),mcrypt_enc_get_iv_size($td));
            mcrypt_generic_init($td, $key, $iv);
            $clear_data = mdecrypt_generic($td, $cyphered);
            mcrypt_generic_deinit($td);
            mcrypt_module_close($td);
            $clear_data = str_replace(str_repeat(chr(8),8),'',$clear_data);
            $clear_data = str_replace(chr(0),'',$clear_data);
            return($clear_data);
        }

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