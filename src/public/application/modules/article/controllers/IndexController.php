<?php

class Article_IndexController extends Zend_Controller_Action
{

    public function init()
    {
		if(!hasAccess("article")){
			$this->redirect($this->view->Url(Array(),"NoAccess",true));
		}
    }

    public function indexAction()
    {

    }

	public function showAction()
	{
		// @ Micha, check mal warum das hier ist
		//$auth = Zend_Auth::getInstance()->getIdentity();
		//echo $auth->username;

		//echo Zend_Auth::getInstance()->getIdentity()->role;
		//exit;

		$request = $this->getRequest();

		$lang = $request->getParam("lang","en");
		$alias = $request->getParam("alias","");

		$obj_a = new Article_Model_Articles();
		$obj_ad = new Article_Model_ArticleDetails();

		$select = $obj_a->getAdapter()->select();
		$select->from(Array("a"=>"articles"),Array("article_published" => "published", "key" => "key"));
		$select->join(Array("ad" => "article_details"), "a.article_id=ad.article_id" );
		$select->where("ad.lang=?",$lang);
		$select->where("ad.alias=?",$alias);

		$article = $obj_a->getAdapter()->fetchRow($select);
		if($article)
		{
			if( hasAccess("acp_article") || $article['article_published'] ==1)
			{
				if(hasAccess("acp_article") || $article['published'] == 1)
				{
					if($article['image']=="")
					{
						if(	file_exists(APPLICATION_PATH."/../media/article/".$article['article_id']."/".$lang.".jpg")	)
						{
							$article['image'] = $this->view->baseUrl()."/media/article/".$article['article_id']."/".$lang.".jpg";
						}
						elseif(	file_exists(APPLICATION_PATH."/../media/article/".$article['article_id']."/".$lang.".png") 	)
						{
							$article['image'] = $this->view->baseUrl()."/media/article/".$article['article_id']."/".$lang.".png";
						}
					}
					$this->view->article = $article;

					// Language Switch

					$translate = Zend_Registry::get("Zend_Translate");
					$oldLocale = $translate->getLocale();

					$select = $obj_ad->select();
					$select->where("article_id=?",$article['article_id']);
					$select->where("lang in (?)",getAllLanguages("frontend"));
					$articles = $obj_ad->fetchAll($select);

					foreach($articles as $item)
					{
						$translate->setLocale($item->lang);
						$url = $this->view->url( Array("lang"=>$item->lang,"alias"=>$item->alias),"Article_Show" );
						$this->view->LanguageSwitch()->setUrlLanguage($item->lang,$url);
					}

					$translate->setLocale($oldLocale);
				} else {
					//$this->view->render("error/error.phtml");
					$this->forward("error","error","article");
				}
			} else {
				//$this->view->render("error/error.phtml");
				$this->forward("error","error","article");
			}
			//exit;
		} else {
			//$this->view->render("default/error/notfound.phtml");
			$this->forward("error","error","article");
		}
	}
}