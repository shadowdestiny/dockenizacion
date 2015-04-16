<?

class Application_Plugin_Lottery extends Zend_Controller_Plugin_Abstract
{

	public function preDispatch(Zend_Controller_Request_Abstract $request)
    {	
		$obj_c = new Default_Model_Currencies();
		$cache = Zend_Registry::get("Zend_Cache");
		
		$cache_id = "currency_rates";
		
		if($cache->test($cache_id))
		{
			$data = $cache->load($cache_id);
		}
		else
		{
			$data = $obj_c->fetchAll("active=1");
			$cache->save($data->toArray(),$cache_id,Array(),3600);
		}
		
		foreach($data as $item)
		{
			$rates_currency[$item['currency']]=$item['rate'];
			$rates_locale[$item['default_locale']]=$item['rate'];
		}
		Zend_Registry::set("Currency_Rates_Currency",$rates_currency);
		Zend_Registry::set("Currency_Rates_Locale",$rates_locale);
		
	}
		
}