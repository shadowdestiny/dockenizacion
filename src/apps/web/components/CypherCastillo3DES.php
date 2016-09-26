<?php
namespace EuroMillions\web\components;
use EuroMillions\web\interfaces\ICypherStrategy;

class CypherCastillo3DES implements ICypherStrategy
{

    const PRESHARED = '123456';

    private $cypher;

    public static $cypher_keys = [
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

    public function __construct()
    {
        $this->cypher = \mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_CBC, '');
    }

    public function encrypt($key,$clear)
    {
        $key = bin2hex(self::$cypher_keys[$key]);
        $key = pack("H" . strlen($key), $key);

        if ($key && $clear) {
            $td = mcrypt_module_open(MCRYPT_3DES, '', 'cbc', '');
            $bs = mcrypt_enc_get_block_size($td);
            if ((strlen($clear) % $bs) > 0) {
                $fill = str_repeat(chr(0), 8 - (strlen($clear) % $bs));
            } else {
                $fill = str_repeat(chr(0), 8);
            }
            $clear .= $fill;
            $padding = str_repeat(chr(8), 8);
            $iv = str_repeat(chr(0), mcrypt_enc_get_iv_size($td));
            mcrypt_generic_init($td, $key, $iv);
            $encrypted_data = mcrypt_generic($td, $clear . $padding);
            $cifrado = $encrypted_data;
            mcrypt_generic_deinit($td);
            mcrypt_module_close($td);
            return base64_encode(($cifrado));
        }

    }

    public function decrypt($cyphered, $key)
    {
        $cyphered = base64_decode($cyphered);
        $key = pack("H" . strlen(bin2hex(self::$cypher_keys[$key])), bin2hex(self::$cypher_keys[$key]));

        if ($key && $cyphered) {
            $td = mcrypt_module_open(MCRYPT_3DES, '', 'cbc', '');
            $iv = str_repeat(chr(0), mcrypt_enc_get_iv_size($td));
            mcrypt_generic_init($td, $key, $iv);
            $clear_data = mdecrypt_generic($td, $cyphered);
            mcrypt_generic_deinit($td);
            mcrypt_module_close($td);
            $clear_data = str_replace(str_repeat(chr(8), 8), '', $clear_data);
            $clear_data = str_replace(chr(0), '', $clear_data);
            return ($clear_data);
        }

    }

    public function getSignature($content_cypehered)
    {
        $signature = sha1(base64_decode($content_cypehered).self::PRESHARED);
        return base64_encode($signature);
    }


}
