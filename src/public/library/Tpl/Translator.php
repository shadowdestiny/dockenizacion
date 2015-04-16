<?php

class Tpl_Translator extends Zend_Translate_Adapter
{
    private $_data = array();
	public $dbAdapter;

	    /**
     * gibt die translations der locale zurück
     *
     * @return array
     */

    protected function _loadTranslationData($data, $locale, array $options = array())
	{

		$arrLang=Array("de","en","es","fr","nl");
		
		$cache = Zend_Registry::get("Zend_Cache");
		
		$cache_key = "Translation";
		
		if($cache->test($cache_key))
		{
			$tmpData = $cache->load($cache_key);
		}
		else
		{
		
			require_once(APPLICATION_PATH."/modules/default/models/Translations.php");
			require_once(APPLICATION_PATH."/modules/default/models/TranslationDetails.php");
			$obj_t = new Default_Model_Translations();
			$obj_td = new Default_Model_TranslationDetails();
			
			$arrKeys = $obj_t->fetchAll();
			
			$tmpData = array();

							
			if($arrKeys) 
			{			
				foreach($arrKeys as $key)
				{
					$arrTagList[$key['translation_id']] = $key->key;
				}
								
				foreach($arrLang as $locale => $lang) 
				{
					$select = $obj_td->select();
					$select->where("lang=?",$lang);
					$arrValues = $obj_td->fetchAll($select);
					
					foreach($arrValues as $value)
					{
						$tmpData[$lang] [ $arrTagList[$value['translation_id']] ] = $value['value'];
					}									
				}
			}
			$cache->save($tmpData,$cache_key,Array(),86400);
		}
		
		
		foreach($arrLang as $locale=>$lang)
		{
			$tmpData[$lang]['seo_news_url_keyword']="newsarchive";
			$tmpData[$lang]['seo_article_url_keyword']="articles";
			$tmpData[$lang]['seo_lottery_url_keyword']="euromillions-results";
			$tmpData[$lang]['seo_winners']="winners";
		}
		/*
		$tmpData["de"]['seo_lottery_url_keyword']="euromillion-ergebnisse";
		$tmpData["es"]['seo_lottery_url_keyword']="resultado-euromillones";
		
		
		$tmpData["de"]['seo_winners']="gewinner";
		$tmpData["es"]['seo_winners']="ganadores";
		*/
		
		foreach($arrLang as $locale => $lang) {
			$Territory = Zend_Locale::getTranslationList('Territory', $lang);
			$countries = getCountryList();
			
			foreach ($countries as $key => $value) {
				$tmpData[$lang]["country_".strtolower($key)] = $Territory[$key];
			}

			$Language = Zend_Locale::getTranslationList('Language', $lang);
			foreach ($Language as $key => $value) {
				$tmpData[$lang]["language_".$key] = $value;
			}

			$Month = Zend_Locale::getTranslationList('Month', $lang);
			foreach ($Month as $key => $value) {
				$tmpData[$lang]["month_".$key] = $value;				
			}
		}

		$this->_data = $tmpData;
		$this->_data['de']['asdasd']="ASD %1\$s ASD %2\$s";

		return $this->_data;
    }


    /**
     * gibt translation für den string zurück
     *
     * @return string
     */
    public function translate($str="",$locale=NULL)
	{
		
			$trans = parent::translate($str,$locale);
			if($trans<>"")
			{
			
				if($trans == $str)
				{					
					$logger = Zend_Registry::get("Zend_Log");
					
				}
				$obj_t = new Default_Model_Translations();
				//$obj_t->update(Array("used"=>1),"`key`='".mysql_real_escape_string( $str)."'");
				return $trans;
			} else {			
				//error("translation | Key: ".$str." ist nicht NULL aber leer");
				return $str;
				
			}
    }


    public function toString() 
	{
        return "Database";
    }
}
