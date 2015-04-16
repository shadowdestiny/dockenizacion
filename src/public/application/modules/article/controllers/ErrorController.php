<?php

class Article_ErrorController extends Zend_Controller_Action
{

    public function errorAction()
    {
		$request = $this->getRequest();		
		$lang = $request->getParam("lang","en");
		$alias = $request->getParam("alias","en");
		
		$logger = Zend_Registry::get("Zend_Log");
		$logger->warn("Artikel mit Alias ".$alias." in der Sprache ".$lang." nicht gefunden!");
				
		$obj_a = new Article_Model_Articles();
		$obj_ad = new Article_Model_ArticleDetails();		
		
		$select = $obj_a->getAdapter()->select();
		$select->from(Array("a"=>"articles"),Array("article_published" => "published"));
		$select->join(Array("ad" => "article_details"), "a.article_id=ad.article_id" );
		$select->where("ad.lang=?",$lang);
		$select->where("a.key=?","error_404");
		$select->where("a.published = ?",1);
		$select->where("ad.published = ?",1);
				
		$article = $obj_a->getAdapter()->fetchRow($select);

		if($article)
		{					
			$this->view->title = $article['title'];		
			$this->view->content = $article['content'];			
		} else {
		
		}
    }
}