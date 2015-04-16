<?
/*
$n = 10;
$k = 5;

$x = fak($n) / (fak($k ) * fak($n-$k));
echo $x." numbers and  ";

$n = 2;
$k = 2;
$y = fak($n) / (fak($k ) * fak($n-$k));
echo $y." stars";

echo " = ".(($x*$y)*2.35);

exit;

function fak($num)
{
	$ret=1;
	for($i=1;$i<=$num;$i++)
	{
		$ret = ($i*$ret);
	}
	return $ret;
}
exit;
*/

$arr_keys = Array(
0=>"000000000000000000000000000000000000000000000000",
1=>"000000000000000000000000000000000000000000000001",
2=>"000000000000000000000000000000000000000000000002",
3=>"000000000000000000000000000000000000000000000003",
4=>"000000000000000000000000000000000000000000000004",
5=>"000000000000000000000000000000000000000000000005",
6=>"000000000000000000000000000000000000000000000006",
7=>"000000000000000000000000000000000000000000000007",
8=>"000000000000000000000000000000000000000000000008",
9=>"000000000000000000000000000000000000000000000009",
);


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
    
$clear = '<?xml version="1.0" encoding="UTF-8"?>
<ticket type="6" date="140509" bets="1" price="2.00">
<id>2014050900002</id>
<combination>
	<number>1</number>
	<number>2</number>
	<number>3</number>
	<number>4</number>
	<number>5</number>
	<star>1</star>
	<star>2</star>
</combination>
</ticket>';

$key =$arr_keys[5];
//$numclave='000000000000000000000000000000000000000000000000';
$numclave=5;

//$numclave=2;
// '000000000000000000000000000000000000000000000000';
$preshared = '1234567890';            

$micrtime = explode(" ",microtime());
$idsesion = substr(date("YmdHis").substr($micrtime[0],2),0,20);

$cifrado = base64_encode(encryptString($clear, $key));

$xmlstream ='<?xml version="1.0" encoding="UTF-8"?>
<message>
	<operation id="'.$idsesion.'" key="'.$numclave.'" type="1">
	<content>'.$cifrado.'</content>
	</operation>';
	
$inipos = strpos($xmlstream,'<operation');
$endpos = strpos($xmlstream,'</operation>', $inipos) + 12;
$operation = substr($xmlstream,$inipos, ($endpos-$inipos));
	
$signature = sha1(base64_decode($cifrado).$preshared);

$signature = base64_encode($signature);

$xmlstream.='
	<signature>'.$signature.'</signature>
</message>';

$ch = curl_init();

curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_URL,'https://www.loteriacastillo.com/euromillions/');
curl_setopt($ch, CURLOPT_POSTFIELDS,$xmlstream);        
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

$result = curl_exec ($ch);
curl_close ($ch);
//echo $result;
//exit;



$obj_ret = simplexml_load_string($result);
$ret_key = (int) $obj_ret->operation->attributes()[2];
$ret_key = $arr_keys[$ret_key];


$arr =   decryptString( bin2hex(base64_decode((string) $obj_ret->operation->content)), $ret_key);
var_dump($arr);
/*
<?xml version="1.0" encoding="UTF-8"?>
<acknowledge>
<id>20140414145504829195</id>
<status>
KO</status>
<message>
Validation error.</message>
</acknowledge>
*/
?>