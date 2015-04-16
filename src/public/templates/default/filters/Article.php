<?php

class Zend_View_Filter_Article
{
	public $list = Array();
	public $lang = "en";
	
	public function Zend_View_Filter_Article()
	{
		$cache = Zend_Registry::get("Zend_Cache");
		
		$this->lang = Zend_Registry::get("lang");

		$cache_key = "article_filter_urls_".$this->lang;
		
		if($cache->test($cache_key))
		{
			$this->list = $cache->load($cache_key);
		} else {
			$obj_ad = new Article_Model_ArticleDetails();
			$obj_a = new Article_Model_Articles();
			$select = $obj_ad->getAdapter()->select();
/*
$select = $obj_ad->select();
$select->where("lang=?","en");
$data = $obj_ad->fetchAll($select);
echo $data->count();
foreach($data as $i)
{
		$arrData=Array(
		"key"=>$i->alias
		);
		$obj_a->update($arrData,"article_id=".$i->article_id);
}
exit("E");
*/

			$select->from(Array("a"=>"articles"),Array("key"));
			$select->join(Array("ad"=>"article_details"),"ad.article_id=a.article_id",Array("alias"));
			$select->where("ad.lang=?",$this->lang);
			$data = $obj_ad->getAdapter()->fetchAll($select);
			
			if($data)
			{
				$this->list = $data;
				$cache->save($data,$cache_key,Array("article"),86400);
			}						
		}	

	}

	public function filter($buffer)
	{
			
		if(is_array($this->list)) 
		{
			$layout = Zend_Layout::getMvcInstance();
			$view = $layout->getView();
			
			foreach($this->list as $article)
			{
				$search[] ='{{article_url::'.$article['key'].'}}';				
				$replace[] = $view->url(Array("lang" => $this->lang, "alias"=>$article['alias']),"Article_Show");				
			}

			//print_r($search);
			//print_r($replace);
			//exit;
			$buffer = str_replace($search, $replace, $buffer);			
			
			
		}

		return $buffer;
	}
}
?>