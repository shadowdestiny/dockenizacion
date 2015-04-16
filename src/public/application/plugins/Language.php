<?
// default country  GB
// default language   en
// default currency euro

class Application_Plugin_Language extends Zend_Controller_Plugin_Abstract
{
	public $default_country = "AF";
	public $default_lang = "en";
	
	public $default_currency_locale="de_DE";
	public $default_currency  = "EUR";	
	
	public $default_locale = "en_GB";

	
	public function preDispatch(Zend_Controller_Request_Abstract $request)
    {	

	}
	
	public function routeStartup(Zend_Controller_Request_Abstract $request)
    {
	
		$translate = Zend_Registry::get('Zend_Translate');
	
		$obj_c = new Default_Model_Currencies();	
		$currencyList = $obj_c->getCurrencyList();
		
		Zend_Layout::startMvc();
		$layout = Zend_Layout::getMvcInstance();
		$view = $layout->getView();	
		$view->currencyList = $currencyList;
		
		$arrLang=getAllLanguages();
	
		if($request->isPost() && $request->getParam("act")=="switchlang")
		{			
			$post=$request->getPost();			
			if(in_array($post['lang'],$arrLang))
			{
			
				if ($translate->isAvailable($post['lang']))
				{

										
					Settings::set("S_lang",$post['lang']);
					Settings::set("C_lang",$post['lang']);
					
					if( !array_key_exists($post['currency'],$currencyList))
					{
						$post['currency'] =$this->default_currency;
					}
					Settings::set("S_currency",$post['currency']);
					Settings::set("C_currency",$post['currency']);

					$this->updateAuth($post['lang'],$post['currency']);

					header("Location: ".$request->getBaseUrl()."/".$post['lang']."/");
					exit();
				}				
			}			
		}
	
	
		$locale = new Zend_Locale();
		Settings::set("S_locale",$locale);
		Settings::set("C_locale",$locale);
		
		// detect lang based on url
		$url = $request->getRequestUri();
		$url = str_replace(addslashes($request->getBaseUrl()),"",$url);		
		
		
		// Wenn eingelogt
		if(Zend_Auth::getInstance()->hasIdentity()) 
		{		
				
			$identity = Zend_Auth::getInstance()->getIdentity();
			
			if(trim($url,"/")=="")
			{						
				if(
					$translate->isAvailable($identity->lang)
					&& in_array( $identity->lang,$arrLang)
				)
				{
					header("Location: ".$request->getBaseUrl()."/".$identity->lang."/");
					exit();			
				} else {					
					$this->updateAuth($this->default_lang,$identity->currency);
					header("Location: ".$request->getBaseUrl()."/en/");
					exit();						
				}
			}
			
			
			// check all
			$update=false;
			if(
				$translate->isAvailable($identity->lang)
				&& in_array( $identity->lang,$arrLang)
			)
			{
				$lang = $identity->lang;
			} else {
				$lang=$this->default_lang;
				$update=true;
			}
	
			$country = $identity->country;
			
			$currency = $identity->currency;			
			if(!array_key_exists ($currency,$currencyList))
			{			
				$currency=$this->default_currency;
				$update=true;
			}
			
			Settings::set("S_lang",$lang);
			Settings::set("C_lang",$lang);
			
			Settings::set("S_country",$country);
			Settings::set("C_country",$country);
			
			Settings::set("S_currency",$currency);
			Settings::set("C_currency",$currency);
			
			if($update)
			{
				$this->updateAuth($lang,$currency);
			}

		}
		// cookie
		elseif
		(
			Settings::get("C_lang")
			 && Settings::get("C_currency")
			 && Settings::get("C_country")
			)
		{		
		
			if(trim($url,"/")=="")
			{						
				if(in_array( Settings::get("C_lang"),$arrLang))
				{
					Settings::set("S_lang",Settings::get("C_lang"));
					Settings::set("S_country",Settings::get("C_country"));
					Settings::set("S_currency",Settings::get("C_currency"));
					
					header("Location: ".$request->getBaseUrl()."/".Settings::get("C_lang")."/");
					exit();			
				} else {
					Settings::set("C_lang",$this->default_lang);
					header("Location: ".$request->getBaseUrl()."/en/");
					exit();			
				}
			}
			
			if( $translate->isAvailable(Settings::get("C_lang"))
				&& in_array( Settings::get("C_lang"),$arrLang))
			{						
				Settings::set("S_lang", Settings::get("C_lang"));
				Settings::set("S_country",Settings::get("C_country"));
				Settings::set("S_currency",Settings::get("C_currency"));
			}
			else
			{
				Settings::set("S_lang", $this->default_lang);
				Settings::get("C_lang",$this->default_lang);
				header("Location: ".$request->getBaseUrl()."/en/");
				exit();			
			}

			$lang = Settings::get("S_lang");
			$country = Settings::get("S_country");
			$currency = Settings::get("S_currency");
			
				
			/*
			// reset
			Settings::set("S_lang", "");
			Settings::set("S_country","");
			Settings::set("S_currency","");
			Settings::set("C_lang", "");
			Settings::set("C_country","");
			Settings::set("C_currency","");
			*/						
			
		}
		else
		{
		
			// detect lang based on url
			
			if(trim($url,"/")=="")
			{			
				if($locale->isLocale($locale,true))
				{
					$lang = $locale->getLanguage();
				} else {
					$lang=$this->default_lang;
				}
				
				if (
					$translate->isAvailable($lang)
					&& in_array( $lang,$arrLang)
				)
				{
					header("Location: ".$request->getBaseUrl()."/".$lang."/");
					exit();
				} else {
					header("Location: ".$request->getBaseUrl()."/en/");
					exit();
				}
			}
			else
			{
				// call a long url
				

				$locale =new Zend_Locale();
				if(Zend_Locale::isLocale($a,true,true))
				{
					if($locale->getRegion()<>"" && $locale->getLanguage()<>"")
					{
						$country=$locale->getRegion();
						$lang=$locale->getLanguage();						
					} else {
						$locale= new Zend_Locale($this->default_locale);
						$lang=$this->default_lang;
						$country=$this->default_country;
					}
				} else {
					$locale= new Zend_Locale($this->default_locale);
					$lang=$this->default_lang;
					$country=$this->default_country;
				}
									
				$curr = new Zend_Currency($locale);
				$currency = $curr->getShortName();
				
				
				if( substr($url,0,1)=="/" && substr($url,3,1)=="/")
				{
					$langTmp = substr($url,1,2);
					$other_lang = $langTmp;				
					if($lang<>$other_lang)
					{
						$lang = $other_lang;
					}
				}
				
				if (
					$translate->isAvailable($lang)
					&& in_array($lang,$arrLang)
				)
				{

				}
				else
				{
					header("Location: ".$request->getBaseUrl()."/en/");
					exit();
				}
			}
			
			// set cookie and session
			Settings::set("S_lang", $lang);
			Settings::set("C_lang", $lang);
			Settings::set("S_country",$country);
			Settings::set("C_country",$country);
			Settings::set("S_currency",$currency);
			Settings::set("C_currency",$currency);
		}		
		
		
		$translate->setLocale($lang);
		$request->setParam("lang",$lang);
		
		
		// set defaults
		
		Zend_Registry::set("lang",$lang);		 		
		
		Zend_Registry::set("Zend_Locale",$locale);
		
		
				
		//Zend_Currency::set($currency);
			
		
		// router
        $front = Zend_Controller_Front::getInstance();
        $router = $front->getRouter();
        $router->setGlobalParam('lang', $lang);
		Zend_Registry::set("Zend_Translate",$translate);
		
		
		Settings::set("C_locale",$locale->toString());		
		Settings::set("S_locale",$locale->toString());		
		
		// Currency
		
		if( isset($currencyList[Settings::get("S_currency")]))
		{
			$currency_locale = $currencyList[Settings::get("S_currency")]['default_locale'];
			$pattern = $currencyList[Settings::get("S_currency")]['format_pattern'];
		} else {
			$currency_locale = $this->default_currency_locale;
			$pattern = $currencyList[ $this->default_currency ]['format_pattern'];
		}
		
		$obj_currency = new Zend_Currency($currency_locale);
		$obj_currency->setService("Tpl_CurrencyConvert");			

		
		Zend_Registry::set("Zend_Currency",$obj_currency);

    }
	
	public function updateAuth($lang,$currency)
	{	
		if( Zend_Auth::getInstance()->hasIdentity()) 
		{
			$identity = Zend_Auth::getInstance()->getIdentity();
			
			$user_id = $identity->user_id;
						
			$obj_u=new User_Model_User();
			$data = $obj_u->fetchRow("user_id=".$user_id);
			$data->lang=$lang;
			$data->currency=$currency;
			
			if($obj_u->update($data->toArray(),"user_id=".$user_id))
			{
				$identity = Zend_Auth::getInstance()->getIdentity();
				$storage = Zend_Auth::getInstance()->getStorage();
				
				$identity->lang=$lang;
				$identity->currency=$currency;
				
				$storage->write($identity);
				$identity = Zend_Auth::getInstance()->getIdentity();
				
				return true;
			}				
			else
			{
				return false;
			}
		}	
		else
		{
			
		}
	}
	
	public function postDispatch( Zend_Controller_Request_Abstract $request )
	{

	}
	
}