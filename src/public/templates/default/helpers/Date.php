<?

class Zend_View_Helper_Date
{
	public $view;

	public function Date($date=NULL, $type = '')
	{
		$language = Settings::get('S_lang');

		switch ($language){
			case 'de':
				$locale = new Zend_Locale('de_DE');
				break;
			case 'fr':
				$locale = new Zend_Locale('fr_FR');
				break;
			case 'es':
				$locale = new Zend_Locale('es_ES');
				break;
			case 'nl':
				$locale = new Zend_Locale('nl_NL');
				break;
			case 'en':
			default:
				$locale = new Zend_Locale('en_GB');
				break;
			}



		if($date == NULL)
		{
			$date = time();
		}


		$format_date = new Zend_Date($date, false, $locale);

		switch($type) {
			case "time": // 15.06.12 10:30
				$transDate = $format_date->get(Zend_Date::DATETIME_SHORT);
				break;
			case "long": // Freitag, 13. Februar 2009
				$transDate = $format_date->get(Zend_Date::DATE_FULL);
				break;
			case "medium":  //d.m.Y
				$transDate = $format_date->get(Zend_Date::DATE_SHORT);
				break;
			case "daymonth":  //d.m.Y
				$transDate = $format_date->get(Zend_Date::DAY_SHORT) .  '/' . $format_date->get(Zend_Date::MONTH_SHORT);
				break;
			case "monthname":  //d.m.Y
				$transDate = $format_date->get(Zend_Date::MONTH_NAME);
				break;
			case "timestamp":  //d.m.Y
				$transDate = $format_date->getTimestamp();
				break;
			default: //d.m.Y
				 $transDate = $format_date->get(Zend_Date::DATES);
			break;
		}
		return $transDate;
	}

	public function setView(Zend_View_Interface $view)
	{
		$this->view = $view;
	}
}