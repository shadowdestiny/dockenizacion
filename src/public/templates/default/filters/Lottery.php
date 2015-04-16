<?php

class Zend_View_Filter_Lottery
{
	public $list = Array();
	public $lang = "en";
	
	public function Zend_View_Filter_Lottery()
	{
		$cache = Zend_Registry::get("Zend_Cache");
		
		$this->lang = Zend_Registry::get("lang");

		$cache_key = "lottery_filter_urls_".$this->lang;
		
		if($cache->test($cache_key))
		{		
			$this->list = $cache->load($cache_key);
		} else {

			$obj_d = new Lottery_Model_Euromillions_Draws();
			$select = $obj_d->select();
			$select->where("published=1");
			$data = $obj_d->fetchAll($select);
			
			if($data)
			{
				$this->list = $data->toArray();
				$cache->save($data->toArray(),$cache_key,Array("lottery"),86400);
			}						
		}	
	}

	public function filter($buffer)
	{	

		if(is_array($this->list)) 
		{
			$layout = Zend_Layout::getMvcInstance();
			$view = $layout->getView();

			$search[] ='{{lottery_url::latest}}';
			$replace[] = $view->url(Array("lang" => $this->lang,),"Lottery_Index");
				
			foreach($this->list as $draw)
			{
				$search[] ='{{lottery_url::'.$draw['draw_id'].'}}';				
				$replace[] = $view->url(Array("lang" => $this->lang, "draw_id"=>$draw['draw_id'],"date"=>$draw['draw_date']),"Lottery_Show");				
			}
			$buffer = str_replace($search, $replace, $buffer);
		}
		return $buffer;
	}
}
?>