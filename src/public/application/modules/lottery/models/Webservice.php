	<?

class Lottery_Model_LC_Webservice extends Zend_Db_Table_Abstract
{
	var $arr_keys = Array();
	
	public function init()
	{
		$this->arr_keys = Array(
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
	
	}

	public function validateTicket($arrData)
	{

		if(
			isset($arrData)
			&& is_array($arrData['numbers'])
			&& is_array($arrData['stars'])
			&& isset($arrData['date'])
			&& isset($arrData['user_id'])
			&& isset($arrData['customer_id'])
		)
		{
			$date = new Zend_Date($arrData['date']);
			$strDate = $date->get(Zend_Date::YEAR_SHORT);
			$strDate.= $date->get(Zend_Date::MONTH);
			$strDate.= $date->get(Zend_Date::DAY);
			
			$start_id = date("YmdGis").$arrData['user_id'].$arrData['customer_id'].rand(100000,200000);
			//$start_id=201405071234;
			
		} else {
			return false;
		}
		
		$clear = '<?xml version="1.0" encoding="UTF-8"?>
		<ticket type="6" date="'.$strDate.'" bets="1" price="2.00">
		<id>'.$start_id.'</id>
		<combination>';
		foreach($arrData['numbers'] as $number) {
			$clear.='
			<number>'.$number.'</number>';
		}
		
		foreach($arrData['stars'] as $star) {
			$clear.='
			<star>'.$star.'</star>';
		}	
		$clear.='	
		</combination>
		</ticket>';

		$key =$this->arr_keys[5];
		//$numclave='000000000000000000000000000000000000000000000000';
		$numclave=5;

		//$numclave=2;
		// '000000000000000000000000000000000000000000000000';
		$preshared = '1234567890';            

		$micrtime = explode(" ",microtime());
		$idsesion = substr(date("YmdHis").substr($micrtime[0],2),0,20);

		$cifrado = base64_encode($this->encryptString($clear, $key));

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

		$arrLog = Array (
		"method"=>"ValidateTicket",
		"start_id"=>$start_id,
		"start_key"=>$numclave,
		"start_type"=>1,
		"transaction_start_date"=>Zend_Date::now()->toString('yyyyMMddHHmmss'),
		"idsesion"=>$idsesion,
		"customer_id"=>$arrData['customer_id'],
		"user_id"=>$arrData['user_id'],
		"order_id"=>$arrData['order_id'],
		"em_ticket_id"=>$arrData['em_ticket_id'],
		"numbers"=>implode(",",$arrData['numbers']),
		"stars"=>implode(",",$arrData['stars'])
		);
/*
		$xml_return ='<?xml version="1.0" encoding="UTF-8"?><acknowledge><id>20140507174036768005</id><status>KO</status><message>Ticket id (201405071234)already received.</message><drawdate></drawdate></acknowledge>';
*/
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_URL,'https://www.loteriacastillo.com/euromillions/');
		curl_setopt($ch, CURLOPT_POSTFIELDS,$xmlstream);        
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

		$result = curl_exec ($ch);
		curl_close ($ch);
		//exit;
		
		$arrLog["transaction_end_date"] = Zend_Date::now()->toString('yyyyMMddHHmmss');

		$obj_ret = simplexml_load_string($result);		
		$ret_key = (int) $obj_ret->operation->attributes()[2];
		$ret_key = $this->arr_keys[$ret_key];

		$xml_return =   $this->decryptString( bin2hex(base64_decode((string) $obj_ret->operation->content)), $ret_key);
		
		if( $xml_return )
		{
			$obj_return = simplexml_load_string($xml_return);
			if($obj_return)
			{
				$arrLog['lc_id'] = (int) $obj_return->id;
				$arrLog['status'] = (string) $obj_return->status;
				$arrLog['message'] = (string) $obj_return->message;
				
				if($arrLog['status']=="OK")
				{
					$arrLog['ticket_id'] = (string) $obj_return->id;
					$date = new Zend_Date((string) $obj_return->drawdate);
					$arrLog['draw_date'] = $date->toString('yyyyMMddHHmmss');
				} else {
					$arrLog['ticket_id']=0;
				}
				
				$this->log($arrLog);
			}
			else
			{
				alert("No valid XML Object from LC");
				return false;			}
		}
		else
		{
			alert("no correct response from lc");
			return false;
		}
			
		
		//print_r($arrLog);
		//exit;
		//print_r($obj_return);
		
		return $arrLog['ticket_id'];
		
		
		
		//var_dump($arr);
		//$obj_ret
		
		//print_r($arrLog);		
	}
	
	function log($arrData)
	{
		$db = $this->getAdapter();
		try
		{
			$db->insert("lc_log",$arrData);
		} catch (EXCEPTION $e)
		{
			echo $e;
		}
	}
	
	function encryptString($clear, $key) 
	{

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
		
}