<?php

class News_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
		$request = $this->getRequest();
		$ajax = $request->getParam('ajaxload',false);
		if (!hasAccess("news")){
			if ($ajax){
				$notification = $this->view->Notification('notice_permission_denied',true);
				$response = Array("success" => false, "html" => $notification);
				echo Zend_Json::encode($response);exit;
			} else {
				$this->redirect($this->view->Url(Array(),"NoAccess",true));
			}
		}
    }


    public function indexAction()
    {

		$request = $this->getRequest();

		$lang = $request->getParam("lang","en");

		$page = $request->getParam("ajaxpage",0);
		if($page==0)
		{
			$page = $request->getParam("page",1);
		}


		$obj_n = new News_Model_News();
		$obj_nd = new News_Model_NewsDetails();

		$select =$obj_n->getAdapter()->select();
		$select->from( Array("n" => "news"), Array("news_id"));
		$select->join(Array("nd"=>"news_details"),"nd.news_id=n.news_id",Array());

		$select->where("n.published=1");
		$select->where("nd.published=1");
		$select->where("nd.lang=?",$lang);
		$select->where("nd.published_on <= ".time());

		$select->order("nd.published_on desc");


		$data = $obj_n->getAdapter()->fetchAll($select);


		$paginator = Zend_Paginator::factory($data);
		$paginator->setItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);
		$this->view->pageCount = $paginator->getPages()->pageCount;
		$this->view->currentPage = $page;

		$this->view->paginator = $this->view->paginationControl(
				$paginator,
				'Sliding',
				'default/pagination/pagination.phtml'
				);

		$list = array_slice($data,(($page-1)*$paginator->getItemCountPerPage() ), $paginator->getItemCountPerPage());

		if($list)
		{
			foreach($list as $item)
			{

				$select = $obj_nd->select();
				$select->where("news_id=?",$item['news_id']);
				$select->where("lang=?",$lang);
				$news = $obj_n->fetchRow($select);

				$tmp = $news->toArray();
				$tmp['detail_link'] = $this->view->url(Array("alias"=>$news['alias']),"News_Show");


				$arrList[]=$tmp;
			}
		}
		
		$this->view->list = $arrList;

		$total = count($data);
		$this->view->countTotal = $total;
		$this->view->countOnPage = count( $list );
		$this->view->page = $page;

		// Language Switch
		$translate = Zend_Registry::get("Zend_Translate");
		$oldLocale = $translate->getLocale();

		$arrLang = getAllLanguages("frontend");
		foreach($arrLang as $lang)
		{
			$translate->setLocale($item->lang);
			$url = $this->view->url( Array("lang"=>$lang),"News_Index" );
			$this->view->LanguageSwitch()->setUrlLanguage($lang,$url);
		}
		$translate->setLocale($oldLocale);

		$obj_s = new Default_Model_Stats();
		if($request->getParam("ajaxload",0))
		{
			$obj_s->set("show_news_ajaxload");
			$this->view->ajaxload=1;
			$html = $this->view->render("index/index.phtml");
			echo Zend_Json::encode(Array('success' => true,'html' =>$html));
			exit;
		} else {
			$obj_s->set("show_news");
		}
    }

	public function showAction()
	{
		$request = $this->getRequest();

		$lang = $request->getParam("lang","en");
		$alias = $request->getParam("alias","");

		$obj_n = new News_Model_News();
		$obj_nd = new News_Model_NewsDetails();

		$select = $obj_n->getAdapter()->select();
		$select->from(Array("n"=>"news"),Array("news_published" => "published"));		$select->join(Array("nd" => "news_details"), "n.news_id=nd.news_id" );
		$select->where("nd.lang=?",$lang);
		$select->where("nd.alias=?",$alias);


		$news = $obj_n->getAdapter()->fetchRow($select);

		if($news)
		{
			if(hasAccess("acp_news") || $news['news_published'] ==1)
			{
				if(hasAccess("acp_news") || $news['published'] == 1)
				{
					// check for images (for old news)
					if(	file_exists(APPLICATION_PATH."/../media/news/".$news['news_id']."/".$lang.".jpg")	)
					{
						$news['image'] = $this->view->baseUrl()."/media/news/".$news['news_id']."/".$lang.".jpg";
					}
					elseif(	file_exists(APPLICATION_PATH."/../media/news/".$news['news_id']."/".$lang.".png") 	)
					{
						$news['image'] = $this->view->baseUrl()."/media/news/".$news['news_id']."/".$lang.".png";
					}

					
					$this->view->news = $news;
				} else {
					$this->forward("error","error","news");
				}
				$obj_s = new Default_Model_Stats();
				$obj_s->set("show_news_single");
			} else {
				$this->forward("error","error","news");
			}
			//exit;
		} else {
			$this->forward("error","error","news");
		}
	}
}