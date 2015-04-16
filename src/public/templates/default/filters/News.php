<?php

class Zend_View_Filter_News
{
	public $list = Array();
	public $lang = "en";
	
	public function Zend_View_Filter_News()
	{
		$cache = Zend_Registry::get("Zend_Cache");
		
		$this->lang = Zend_Registry::get("lang");

		$cache_key = "news_filter_urls_".$this->lang;
		
		if($cache->test($cache_key))
		{
			$this->list = $cache->load($cache_key);
		} else {
			$obj_nd = new News_Model_NewsDetails();
			$obj_n = new News_Model_News();
			$select = $obj_nd->getAdapter()->select();

			$select->from(Array("n"=>"news"),Array("news_id"));
			$select->join(Array("nd"=>"news_details"),"nd.news_id=n.news_id",Array("alias"));
			$select->where("nd.lang=?",$this->lang);
			$data = $obj_nd->getAdapter()->fetchAll($select);
		
			if($data)
			{
				$this->list = $data;
				$cache->save($data,$cache_key,Array("news"),86400);
			}						
		}	

	}

	public function filter($buffer)
	{
		
		
		if(is_array($this->list)) 
		{
			$layout = Zend_Layout::getMvcInstance();
			$view = $layout->getView();

			$search[] ='{{news_url::latest}}';				
			$replace[] = $view->url(Array("lang" => $this->lang),"News_Index");				
			
			foreach($this->list as $news)
			{
				$search[] ='{{news_url::'.$news['news_id'].'}}';				
				$replace[] = $view->url(Array("lang" => $this->lang, "alias"=>$news['alias']),"News_Show");				
			}
			$buffer = str_replace($search, $replace, $buffer);
		}
		return $buffer;
	}
}
?>