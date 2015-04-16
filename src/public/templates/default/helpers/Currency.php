<?

class Zend_View_Helper_Currency
{
	public $view;

	public function Currency()
	{
		return $this;

		/**Ich brauche:
		 * 1. Nur das Symbol
		 * 2. Nur den Betrag
		 * **/
	}

	public function get($value="0.00",$precision=2,$with_currency=true)
	{
		$obj_currency = Zend_Registry::get("Zend_Currency");
		$currency = clone $obj_currency;

		//$currency->clearCache();
		if($value<>"")
		{

			$pattern = $this->view->currencyList[Settings::get("S_currency")] ['format_pattern'];
			$default_locale = $this->view->currencyList[Settings::get("S_currency")] ['default_locale'];

			//$currency->setService("Tpl_CurrencyConvert");


			//$format = Zend_Locale_Data::getContent("cs_CZ", 'currencynumber');
			//echo $format;
			//exit;


			//echo $format;
			//exit;


			//$currency->setFormat(Array("format"=> $pattern));
			//$currency->setFormat(array('precision' => $precision,"display"=>$display ));

			$currency->setValue($value,"EUR");

			//$value = $currency->toString();
			$value = $currency->getValue();

			if($with_currency){
				$value = Zend_Locale_Format::toNumber($value, array(
					'locale'        => $default_locale,
					'number_format' => $pattern,
					'precision'     => $precision));
			}

			return $value;
		} else {
			return "";
		}
	}

	public function getSymbol($value = '0'){
		$obj_currency = Zend_Registry::get("Zend_Currency");
		$currency = clone $obj_currency;

		$pattern = $this->view->currencyList[Settings::get("S_currency")] ['format_pattern'];
		$default_locale = $this->view->currencyList[Settings::get("S_currency")] ['default_locale'];

		$currency->setValue($value,"EUR");


		$currency->setFormat(array('display' => Zend_Currency::NO_SYMBOL));

		$value = $currency->getShortName();
		return $value;
	}

	public function setView(Zend_View_Interface $view)
	{
		$this->view = $view;
	}
}
