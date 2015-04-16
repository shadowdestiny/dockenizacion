<?

class Zend_View_Helper_LanguageSwitch
{
	public $view;

	public $urls = Array();

	public function LanguageSwitch()
	{
		return $this;
	}

	public function getLinks()
	{

		$c= '';
		$this->view->displayLanguage = Zend_Registry::get('lang');
		$languages = getAllLanguages();
		foreach($languages as $lang){
			$active = ($this->view->displayLanguage == $lang) ? 'active-' : '';

			if(isset($this->urls[$lang]))
			{
				$c.= '<a href="'.$this->urls[$lang].'"><div class="flag-sprite flag-sprite-' . $active . $lang .'"></div></a>';
			} else {
				$c.= '<a href="'. $this->view->baseUrl() . '/' . $lang .'/"><div class="flag-sprite flag-sprite-' . $active . $lang .'"></div></a>';
			}

		}

		$this->view->displayLanguage = Zend_Registry::get('lang');
		return $c;
	}


	public function setUrlLanguage($lang="",$url="")
	{
		if($lang<>"" && $url<>"")
		{
			$this->urls[$lang]=$url;
		}
	}

	public function setView(Zend_View_Interface $view)
	{
		$this->view = $view;
	}
}
