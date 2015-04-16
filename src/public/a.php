<?php

function encryptString($clear, $key) {

    $key = pack("H" . strlen($key), $key);

    if ($key && $clear) {
        $td = mcrypt_module_open(MCRYPT_3DES, '', 'cbc', '');
        $bs = mcrypt_enc_get_block_size($td);
        if ((strlen($clear) % $bs) > 0) {
            $fill = str_repeat(chr(0),8-(strlen($clear) % $bs));
        } else {
            $fill = str_repeat(chr(0),8);
        }
        $clear .= $fill;
        $padding = str_repeat(chr(8),8);
        $iv = str_repeat(chr(0),mcrypt_enc_get_iv_size($td));
        mcrypt_generic_init($td, $key, $iv);
        $encrypted_data = mcrypt_generic($td, $clear.$padding);
        $cifrado = $encrypted_data;
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        return($cifrado);
    }

}

function decryptString($cyphered, $key) {

    $key = pack("H" . strlen($key), $key);

    $cyphered   = pack("H" . strlen($cyphered), $cyphered);

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
    
    $clear = '<?xml version="1.0" encoding="UTF-8"?><ticket type="1" date="140227" bets="1" price="1.00"><id>2330000001</id><combination><number>3</number><number>12</number><number>24</number><number>27</number><number>30</number><number>35</number></combination></ticket>';  
    $key = '000000000000000000000000000000000000000000000000';
    $preshared = '1234567890';            





	
    $micrtime = explode(" ",microtime());
    $idsesion = substr(date("YmdHis").substr($micrtime[0],2),0,20);

    $cifrado = base64_encode(encryptString($clear, $key));
    $xmlstream ='<?xml version="1.0" encoding="UTF-8"?><message><operation id="'.$idsesion.'" key="'.$numclave.'" type="1"><content>'.$cifrado.'</content></operation>';
    $inipos = strpos($xmlstream,'<operation');
    $endpos = strpos($xmlstream,'</operation>', $inipos) + 12;
    $operation = substr($xmlstream,$inipos, ($endpos-$inipos));
        
    $signature = sha1(base64_decode($cifrado).$preshared);

    $signature = base64_encode($signature);
    
    $xmlstream.='<signature>'.$signature.'</signature></message>';

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_POST,1);
    curl_setopt($ch, CURLOPT_URL,'https://www.loteriacastillo.com/euromillions/');
    curl_setopt($ch, CURLOPT_POSTFIELDS,$xmlstream);        
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

    $result = curl_exec ($ch);
    curl_close ($ch);

	//echo $result;
/*
	$result="<?xml version='1.0' encoding='UTF-8'?><message><operation id='20140414124306619119' type='4' key='4'><content>BdeIA2l+wIsBRIP0du4aa7qwNgQ5Z5ADgIzSDN8oHZM1F+M7tG6wz0An6oKVdVLmB0IEUBaX0k/tmQblquk0ISIrn5SLZRm7j3KdeQiFC2WD58yXe36Y4bds8XztIJsEySNdRq8XEP6gKDB9W7el76DqUn5/H9JSi+oF+QMQUiCtm3YSRS599heEQOKX6OxVYAe2W+DQp6FYy4n0f094lg==</content></operation><signature>01b307acba4f54f55aafc33bb06bbbf6ca803e9a</signature></message>";
*/	
	$obj_ret = simplexml_load_string($result);
//echo $obj_ret->operation->content;
	$ret_key = $obj_ret->operation->attributes()[2];
	echo $ret_key;
	exit;
	
	//print_r(  $obj_ret->operation);

	$arr =   decryptString( bin2hex(base64_decode((string) $obj_ret->operation->content)),(int) $ret_key);
	print_r($arr);
	exit("E");
	//decryptString("BdeIA2l+wIsBRIP0du4aa7qwNgQ5Z5ADgIzSDN8oHZM1F+M7tG6wz0An6oKVdVLmB0IEUBaX0k/tmQblquk0IXUwfCSJp3+wmYBUxjLV8zmiz4aUFKtVo7lM5cWuCXKZy6ZDNdpaJOmgS5axocNRh3R+eDmHBpkYboMIbO4/YDuF0D5IR8Kg/ufC3K6L+DKSzz2Qwp3Oq8qTGD6RlBK8yw==",5);

	echo $a;
	print_r($a);
	//print_r( $xml );
exit("B");

	
	//$a=decryptString($result,$key);
	//print_r($a);
/*
<?xml version='1.0' encoding='UTF-8'?>
<message>
<operation id='20140414114059930627' type='4' key='4'>
<content>
BdeIA2l+wIsBRIP0du4aa7qwNgQ5Z5ADgIzSDN8oHZM1F+M7tG6wz0An6oKVdVLmB0IEUBaX0k/tmQblquk0IZ722ZPN4HGyW3ccJ2+e8KSV6tukZD7SekyuQAcjiRozq+9QbH/f9loOIIa5y3B0VTyNZwMGRi7ok2raYUcNIrQudLIj4G/w4gBHUK78nM7SXK4JQ2ZE7YUDDcx88SqjxQ==
</content>
</operation>
<signature>
01b307acba4f54f55aafc33bb06bbbf6ca803e9a</signature>
</message>
*/


  ?>


