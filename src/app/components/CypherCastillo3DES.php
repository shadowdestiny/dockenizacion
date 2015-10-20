<?php


namespace EuroMillions\components;


use EuroMillions\interfaces\ICypherStrategy;

class CypherCastillo3DES implements ICypherStrategy
{

    private $cypher;

    private $key;

    private $cyphered;

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
        '000000000000000000000000000000000000000000000009'
    ];

    public function __construct()
    {
        $this->cypher =  mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_CBC, '');
        $this->key = array_rand(self::$cypher_keys);
    }

    public function encrypt($clear)
    {

        $bs = mcrypt_enc_get_block_size($this->cypher);

        if ((strlen($clear) % $bs) > 0) {
            $fill = str_repeat(chr(0),8-(strlen($clear) % $bs));
        } else {
            $fill = str_repeat(chr(0),8);
        }
        $clear .= $fill;
        $padding = str_repeat(chr(8),8);
        $iv = str_repeat(chr(0),mcrypt_enc_get_iv_size($this->cypher));
        mcrypt_generic_init($this->cypher, $this->key, $iv);
        $encrypted_data = mcrypt_generic($this->cypher, $clear.$padding);
        $this->cyphered = strtoupper(bin2hex($encrypted_data));
        mcrypt_generic_deinit($this->cypher);
        //close module mcrypt
        mcrypt_module_close($this->cypher);

    }

    public function getCypherResult()
    {
        return $this->cyphered;
    }

    public function decrypt($cyphered, $key)
    {

    }
}