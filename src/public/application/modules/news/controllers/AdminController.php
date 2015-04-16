<?php

class News_AdminController extends Zend_Controller_Action
{

	protected $user_id=0;

    public function init()
    {
		if(!hasAccess("acp") || !hasAccess("acp_news"))
		{
			$this->redirect($this->view->Url(Array(),"NoAccess",true));
		}

        if(Zend_Auth::getInstance()->hasIdentity())
		{
			$auth = Zend_Auth::getInstance()->getIdentity();
			$this->user_id=$auth->user_id;

		} else {
			$this->user_id = 0;
		}
    }

    public function indexAction()
    {
		$request = $this->getRequest();

		$page = $request->getParam("page",1);
		$search = $request->getParam("search","");

		$obj_n = new News_Model_News();
		$obj_nd = new News_Model_NewsDetails();

		$select = $obj_n->getAdapter()->select();
		$select->from(Array("n"=>"news"),Array("*","global_published"=>"n.published","global_creation_date"=>"n.creation_date"));
		$select->join(Array("nd"=>"news_details"),"nd.news_id = n.news_id",Array("*"));


		//$search= "euro" ;
		if($search<>"")
		{
			$select->orwhere("nd.title like (?)","%".$search."%");
			$select->orwhere("nd.alias like (?)","%".$search."%");
			$select->orwhere("nd.description like (?)","%".$search."%");
			$select->orwhere("n.key like (?)","%".$search."%");
			$select->orwhere("nd.header like (?)","%".$search."%");
			$select->orwhere("nd.news_id like (?)","%".$search."%");
			$select->orwhere("nd.alt like (?)","%".$search."%");
			$select->orwhere("nd.meta_keywords like (?)","%".$search."%");
			$select->orwhere("nd.meta_description like (?)","%".$search."%");
			$select->orwhere("nd.page_title like (?)","%".$search."%");
			$select->orwhere("nd.content like (?)","%".$search."%");
		}
		//echo $select;

		$select->order("n.creation_date desc");
		//$select->LimitPage($page,10);

		$list = $obj_n->getAdapter()->fetchAll($select);
//print_r($list[0]);
//exit;
		$arrLang = getAllLanguages("backend");

		$this->view->arrLang = $arrLang;

		if($list)
		{
			foreach($list as $news)
			{
				$availableLangss=Array();

				foreach($arrLang as $lang)
				{
					if($news['lang'] == $lang)
					{
						$availableLangs[]=$lang;

						$arrTmp[$news['news_id']] ['published'] = $news['global_published'];
						$arrTmp[$news['news_id']] ['creation_date'] = $news['global_creation_date'];
						$arrTmp[$news['news_id']] ['news_id'] = $news['news_id'];
						$arrTmp[$news['news_id']] ['key'] = $news['key'];

						$arrTmp[$news['news_id']] ['details'] [$lang] = Array(
						"id"=>$news['id'],
						"published"=>$news['published'],
						"published_on"=>$news['published_on'],
						"creation_date"=>$news['creation_date'],
						"alias"=>$news['alias'],
						"title"=>$news['title'],
						);
					}
				}
			}

			foreach($arrTmp as $news_id=>$news)
			{
				foreach($arrLang as $lang)
				{
					if( isset($news ['details'] [$lang]) )
					{

					} else {
						$arrTmp[$news_id]['missing_languages'][]=$lang;
					}
				}
			}

			$this->view->list = $arrTmp;

		} else {
			$this->view->list = Array();
		}
    }

    public function addAction()
    {
		if(!hasAccess("acp_news_edit")){
			$this->redirect($this->view->Url(Array(),"NoAccess",true));
		}

		$request = $this->getRequest();
		$obj_n=new News_Model_News();
		$obj_nd=new News_Model_NewsDetails();

		$lang = $request->getParam("language","en");
		$news_id = $request->getParam("news_id",0);

		$form = new News_Form_News();

		$check_key = new Zend_Validate_Db_NoRecordExists(array('table' => "news",'field' => 'key'));
		$check_key->setMessage("Key already exists!");


		$check_title = new Model_Validate_Db_NoRecordExists(
			Array(
				'table' => "news_details",
				'fields' => Array("title" ,"lang")
				));

		$check_title->setMessage("Title already exists!");

		if($news_id==0)
		{
			$form->key->addValidator($check_key);
		}

		$form->title->addValidator($check_title);

		if($request->isPost())
		{
			$data = $request->getPost();
			if($form->isValid($data))
			{
				$published_on = new Zend_Date($data['published_on']);
				if($data['news_id']==0)
				{

					$data = $form->getValues();
					$news = Array(
					"creation_date"=>time(),
					"created_by"=>$this->user_id,
					"published"=>$data['published'],
					"published_by"=>$data['author'],
					"published_on"=>$published_on->get(Zend_Date::TIMESTAMP),
					"key"=>$data['key'],
					);

					try
					{
						$news_id = $obj_n->insert($news);
					}
					catch(EXCEPTION $e)
					{
						echo $e;
						$form->populate($data);
					}

				}
				else
				{
					$news_id=$data['news_id'];
				}



				if($news_id>0)
				{
					if($data['image_url']<>"")
					{
						$image = $data['image_url'];
					} else {
						$image="";
					}

					$newsDetails = Array(
					"news_id"=>$news_id,
					"lang"=>$data['lang'],
					"image"=>$image,
					"alias"=>make_alias ($data['title']),
					"title"=>$data['title'],
					"content"=>$data['content'],
					"header"=>$data['header'],
					"page_title"=>$data['page_title'],
					"meta_keywords"=>$data['meta_keywords'],
					"meta_description"=>$data['meta_description'],
					"published"=>$data['published'],
					"published_by"=>$data['author'],
					"published_on"=>$published_on->get(Zend_Date::TIMESTAMP),
					"creation_date"=>time(),
					"created_by"=>$this->user_id,
					);

					try
					{
						if( $obj_nd->insert($newsDetails) )
						{
							// Upload image

							if( $form->image->isUploaded())
							{
								$pathinfo = pathinfo($form->image->getFileName());
								$ext = strtolower( $pathinfo['extension'] );

								$destination = APPLICATION_PATH."/../media/news/".$news_id."/";

								@mkdir($destination);

								if(is_dir($destination))
								{
									$newFilename = "".$data['lang'].".".$ext;

									$form->image->setDestination($destination);

									$form->image->addFilter('Rename', $newFilename);

									if( $form->image->receive() )
									{
										sleep(1);
									}
								}
							}


							header("Location: ".$this->view->url( Array("module"=>"news","controller"=>"admin","action"=>"index"),"",true ));
							exit;
						}
						$form->populate($data);
					} catch(EXCEPTION $e)
					{
						echo $e;
						$form->populate($data);
					}
				}
			} else {
				$form->populate($data);
			}
		}
		else
		{
			if($news_id>0)
			{
				$news = $obj_n->find($news_id);

				if($news)
				{
					$form->key->setValue($news[0]['key']);
					$form->key->setAttrib('readonly', 'readonly');
				}
				else
				{
					exit("ERR:002");
				}

			}



			$date = new Zend_Date();
			$form->published_on->setValue($date->toString("YYYY-MM-dd"));

			$form->lang->setValue($lang);
			$form->news_id->setValue($news_id);
		}

		$form->alias->setAttrib('readonly', 'readonly');
		$form->alias->setRequired(false);
		$form->alias->setValue($this->view->translate("will_filled_automatically"));

		$this->view->form = $form;


    }

	public function editAction()
	{
		if(!hasAccess('acp_news_edit')){
			$this->redirect($this->view->Url(Array(),"NoAccess",true));
		}
		$request = $this->getRequest();
		$obj_n=new News_Model_News();
		$obj_nd=new News_Model_NewsDetails();

		$lang = $request->getParam("language","en");
		$news_id = $request->getParam("news_id",0);

		$form = new News_Form_News();

		$regex = new Zend_Validate_Regex('/^[a-zA-Z0-9\-_]*$/');
		$regex->setMessage("wrong_characters");
		$form->alias->addValidator($regex);


		$check_alias = new Model_Validate_Db_NoRecordExists(
			Array(
				'table' => "news_details",
				'fields' => Array("alias" ,"lang"),
				"exclude"=>Array(
					"field"=>"news_id",
					"value"=>$news_id
					)
				)
			);
		$check_alias->setMessage("alias already exists!");
		$form->alias->addValidator($check_alias);


		if($request->isPost())
		{


			$data = $request->getPost();

			if($form->isValid($data))
			{

				$published_on = new Zend_Date($data['published_on']);

				if($data['news_id'] > 0)
				{
					if($data['image_url']<>"")
					{
						$image = $data['image_url'];
					} else {
						$image="";
					}
					$newsDetails = Array(
					"news_id"=>$news_id,
					"lang"=>$data['lang'],
					"image"=>$image,
					"alias"=>$data['alias'],
					"title"=>$data['title'],
					"content"=>$data['content'],
					"header"=>$data['header'],
					"page_title"=>$data['page_title'],
					"meta_keywords"=>$data['meta_keywords'],
					"meta_description"=>$data['meta_description'],
					"published"=>$data['published'],
					"published_by"=>$data['author'],
					"published_on"=>$published_on->get(Zend_Date::TIMESTAMP),
					"changed_by"=>$this->user_id,
					"change_date"=>time(),
					);

					try
					{
						if( $obj_nd->update ($newsDetails, "news_id=".$news_id." and lang='".$lang."'") )
						{

							// Upload image

							if( $form->image->isUploaded())
							{
															$pathinfo = pathinfo($form->image->getFileName());
								$ext = strtolower( $pathinfo['extension'] );

								$destination = APPLICATION_PATH."/../media/news/".$data['news_id']."/";

								@mkdir($destination);

								if(is_dir($destination))
								{

									$newFilename = "".$data['lang'].".".$ext;

									$form->image->setDestination($destination);

									$form->image->addFilter('Rename', $newFilename);

									if( $form->image->receive() )
									{
										sleep(1);
									}
								}
							}

							header("Location: ".$this->view->url( Array("module"=>"news","controller"=>"admin","action"=>"index"),"",true ));
							exit;
						}
						$form->populate($data);
					} catch(EXCEPTION $e)
					{
						$form->populate($data);
					}
				}
			} else {
				$form->populate($data);
			}
		}
		else
		{
			if($news_id>0 && $lang<>"")
			{
				$news = $obj_n->find($news_id);
				if($news)
				{
					$data = $obj_nd->fetchRow("news_id=".$news_id." and lang='".$lang."'");
					if($data)
					{
						$published_on = new Zend_Date ( $data['published_on'] );

						$newsDetails = Array(
						"news_id"=>$news_id,
						"alias"=>$data['alias'],
						"lang"=>$data['lang'],
						"image_url"=>$data['image'],
						"title"=>$data['title'],
						"content"=>$data['content'],
						"header"=>$data['header'],
						"page_title"=>$data['page_title'],
						"meta_keywords"=>$data['meta_keywords'],
						"meta_description"=>$data['meta_description'],
						"published"=>$data['published'],
						"published_on"=>$published_on->toString("Y-M-d"),
						"author"=>$data["published_by"],
						"key"=>$news[0]['key'],
						);

						$form->populate($newsDetails);

						$form->key->setValue($news[0]['key']);
						$form->key->setAttrib('readonly', 'readonly');
						$form->lang->setValue($lang);
						$form->news_id->setValue($news_id);

						/*
						$check_title = new Model_Validate_Db_NoRecordExists(
						Array(
							'table' => "news_details",
							'fields' => Array("title" ,"lang"),
					        'exclude' => array(
					            'field' => 'title',
					            'value' => $newsDetails['title']
					        )
						));
						$check_title->setMessage("Title already exists!");
						$form->title->addValidator($check_title);
						*/
					} else {
						exit;
					}
				}
				else
				{
					exit;
				}
			}
			else
			{
				exit;
			}

		}
		$this->view->form = $form;
		//echo $form;
		//exit("A");
	}

	public function delAction()
	{
		if(!hasAccess("acp_news_delete")){
			echo $this->view->Notification('notice_permission_denied',true);
			exit;
		}
		$this->_helper->layout->disableLayout();

		$request = $this->getRequest();

		$lang = $request->getParam("language","");
		$news_id = $request->getParam("news_id","");
		$a = $request->getParam("a","");


		if($news_id<>"" && $lang<>"")
		{
			$obj_nd = new News_Model_NewsDetails();
			$select = $obj_nd->select();
			$select->where("news_id=?",$news_id);
			$select->where("lang=?",$lang);

			$news = $obj_nd->fetchRow($select);

			if($news)
			{
				$this->view->lang = $lang;
				$this->view->title = $news->title;

				if($a == "y")
				{
					$obj_nd->delete("news_id='".$news_id."' and lang='".$lang."'");

					$obj_nd = new News_Model_NewsDetails();
					$select = $obj_nd->select();
					$select->where("news_id=?",$news_id);
					$news = $obj_nd->fetchAll($select);
					if($news->count()==0)
					{
						$obj_n = new News_Model_News();
						$obj_n->delete("news_id=".$news_id);
					}

					header("Location: ".$this->view->url( Array("module"=>"news","controller"=>"admin","action"=>"index"),"",true ));
					exit;
				}
			} else {
				header("Location: ".$this->view->url( Array("module"=>"news","controller"=>"admin","action"=>"index"),"",true ));
				exit;
			}
		} else {
			header("Location: ".$this->view->url( Array("module"=>"news","controller"=>"admin","action"=>"index"),"",true ));
			exit;
		}

	}

	public function publishAction()
	{
		if(!hasAccess("acp_news_publish")){
			echo $this->view->Notification('notice_permission_denied',true);
			exit;
		}

		$this->_helper->layout->disableLayout();

		$request = $this->getRequest();

		$lang = $request->getParam("language",0);
		$news_id = $request->getParam("news_id",0);

		$this->view->news_id = $news_id;
		$this->view->lang = $lang;

		if($news_id>0)
		{
			if($lang <> "0")
			{
				$obj_nd = new News_Model_NewsDetails();

				$newsDetail = $obj_nd->fetchRow("news_id=".$news_id." and lang = '".$lang."'");

				if($newsDetail)
				{
					$arrData = Array(
					"published"=>($newsDetail->published? 0:1),
					"changed_by"=>$this->user_id,
					"change_date"=>time()
					);
					$obj_nd->update($arrData,"news_id=".$news_id." and lang='".$lang."'");
					$this->view->published = ($newsDetail->published? 0:1);
				}
				else
				{
					$this->view->error=true;
				}
			}
			else
			{
				$obj_n = new News_Model_News();
				$news = $obj_n->fetchRow("news_id=".$news_id);

				if($news)
				{
					$arrData = Array(
					"published"=>($news->published? 0:1),
					//"changed_by"=>$this->user_id,
					//"change_date"=>time()
					);
					$obj_n->update($arrData,"news_id=".$news_id);
					$this->view->published = ($news->published? 0:1);
				}
				else
				{
					$this->view->error=true;
				}
			}
		} else {
			$this->view->error=true;
		}

	}

}