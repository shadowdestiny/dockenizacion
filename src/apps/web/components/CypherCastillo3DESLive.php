<?php
namespace EuroMillions\web\components;
use EuroMillions\web\interfaces\ICypherStrategy;

class CypherCastillo3DESLive implements ICypherStrategy
{

    const PRESHARED = 'llKA2+sd(23ssDFc';

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
        $dia_keys = [];
        $dia_keys[0] = bin2hex('Kjsnk29JKDl2+adMJSD323as');
        $dia_keys[1] = bin2hex('aDD7732hjkd.W!VklIOniUw5');
        $dia_keys[2] = bin2hex('m&hhrekKNvGOs=i8r4-O?:Ba');
        $dia_keys[3] = bin2hex('.MCrgrFZYB42ZFnw_d?g4OdD');
        $dia_keys[4] = bin2hex('fdUdAV?F*:DN7mzd66+CNt.O');
        $dia_keys[5] = bin2hex('es5D/a8/h2?Tf5329dS3,Pcp');
        $dia_keys[6] = bin2hex('qTw0o9-/Zgd3.q8MkBqq/:Qm');
        $dia_keys[7] = bin2hex('dDA+PpzO-rK52gx;0gMny?Ny');
        $dia_keys[8] = bin2hex('gb=aD/N=ICp.e8xDA1dgRcRF');
        $dia_keys[9] = bin2hex('lMkIprsq%Rr=DC*zP6whtQe9');

        return $dia_keys[$key];
    }


}
