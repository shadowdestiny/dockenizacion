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

            if($key == 0){
                var_dump($clear_data);
            }


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
        $keys[0] = '4b6a736e6b32394a4b446c322b61644d4a53443332336173';
        $keys[1] = '61444437373332686a6b642e5721566b6c494f6e69557735';
        $keys[2] = '6d26686872656b4b4e76474f733d693872342d4f3f3a4261';
        $keys[3] = '2e4d43726772465a594234325a466e775f643f67344f6444';
        $keys[4] = '6664556441563f462a3a444e376d7a6436362b434e742e4f';
        $keys[5] = '657335442f61382f68323f5466353332396453332c506370';
        $keys[6] = '715477306f392d2f5a6764332e71384d6b4271712f3a516d';
        $keys[7] = '6444412b50707a4f2d724b353267783b30674d6e793f4e79';
        $keys[8] = '67623d61442f4e3d4943702e653878444131646752635246';
        $keys[9] = '6c4d6b49707273712552723d44432a7a5036776874516539';
        return $keys[$key];
    }


}
