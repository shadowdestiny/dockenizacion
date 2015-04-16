<?

class Zend_View_Helper_Trans
{
	public $view;

	public function Trans()
	{
		return	$this;
	}
	
	public function getLanguageList($type="frontend")
	{
		$arrLang = getAllLanguages($type);
		$translate = Zend_Registry::get("Zend_Translate");
		
		$current = $translate->getLocale();
		
		foreach($arrLang as $lang)
		{
			if($translate->isAvailable($lang))
			{
				$translate->setLocale($lang);
				$name = $translate->translate("language_".$lang);
				$name = ucfirst($name);
				$arrRet[$name ] = $lang;
			}
		}
		$translate->setLocale($current);
		ksort($arrRet);		
		return $arrRet;
	}

	public function setView(Zend_View_Interface $view) {
		$this->view = $view;
	}
}
