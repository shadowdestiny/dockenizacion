<?php
namespace EuroMillions\web\components;
use EuroMillions\web\interfaces\ICypherStrategy;

class CypherCastillo3DESLive implements ICypherStrategy
{

    const PRESHARED = '881e03X65b4fdfcY1dd18f6c2';

    private $cypher;

    public function __construct()
    {
        $this->cypher = \mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_CBC, '');
    }

    public function encrypt($key,$clear)
    {
        $key = $this->getKeys($key);
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

        if ($key && $cyphered) {
            $key = pack("H" . strlen($this->getKeys($key)),$this->getKeys($key));
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


    private function getKeys($key)
    {
        $keys = [];
        $keys[0] = bin2hex('f67X782a5b18F098ff2e30bb2');
        $keys[1] = bin2hex('d7ee921e4e6b5e5c17a4Pdf2c');
        $keys[2] = bin2hex('422ec44fc8cR288e1e6d5cb25');
        $keys[3] = bin2hex('a05d849c8Vda4e2ebe375508b');
        $keys[4] = bin2hex('42f355U187e943276b22d6c79');
        $keys[5] = bin2hex('f618975092b51d50fc2bK3dff');
        $keys[6] = bin2hex('c9a3d138e26c3be52bf0b7936');
        $keys[7] = bin2hex('6f6Z83acf69806f8e6e1bc02b');
        $keys[8] = bin2hex('4f4ce0a4c34f37b7eadd22cf8');
        $keys[9] = bin2hex('91ac3dDe3e5117b0221Ad49eb');
        return $keys[$key];
    }


}
