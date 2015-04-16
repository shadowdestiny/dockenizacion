<?

class Tpl_CurrencyConvert implements Zend_Currency_CurrencyInterface
{
    public function getRate($from, $to)
	{		
				
		$rates=Zend_Registry::get("Currency_Rates_Currency");
		
				
        if ($from !== "EUR") {
			error_log('Error with currency defination');
			return 1;
        }
 
        if(array_key_exists($to,$rates))
		{
			return $rates[$to];
		}
		else
		{
			error_log("Currency not available");
			return 1;
		}		
    }
}