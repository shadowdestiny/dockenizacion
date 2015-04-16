<?php

class Article_AdminController extends Zend_Controller_Action
{

	protected $user_id=0;

    public function init()
    {
		if(!hasAccess("acp") or !hasAccess("acp_article"))
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

		$search = $request->getParam("search","");

		$obj_a = new Article_Model_Articles();
		$obj_ad = new Article_Model_ArticleDetails();

		$select = $obj_a->getAdapter()->select();
		$select->from(Array("a"=>"articles"),Array("*","global_published"=>"a.published","global_creation_date"=>"a.creation_date"));
		$select->join(Array("ad"=>"article_details"),"ad.article_id = a.article_id",Array("*"));

		//$select->where("a.article_id>0");

		//$search='euromillions.com/im';
		if($search<>"")
		{

			$select->orwhere("ad.title like (?)","%".$search."%");
			$select->orwhere("ad.alias like (?)","%".$search."%");
			$select->orwhere("ad.description like (?)","%".$search."%");
			$select->orwhere("a.key like (?)","%".$search."%");
			$select->orwhere("ad.header like (?)","%".$search."%");
			$select->orwhere("ad.article_id like (?)","%".$search."%");
			$select->orwhere("ad.alt like (?)","%".$search."%");
			$select->orwhere("ad.meta_keywords like (?)","%".$search."%");
			$select->orwhere("ad.meta_description like (?)","%".$search."%");
			$select->orwhere("ad.page_title like (?)","%".$search."%");
			$select->orwhere("ad.content like (?)","%".$search."%");
		}

		$select->order("a.key");

		$list = $obj_a->getAdapter()->fetchAll($select);


		$arrLang = getAllLanguages("backend");

		$this->view->arrLang = $arrLang;

		if($list)
		{
			foreach($list as $article)
			{
				$availableLangss=Array();
				///$arrTmp[$article['article_id']] ['missing_languages']=Array();

				foreach($arrLang as $lang)
				{
					if($article['lang'] == $lang)
					{
						$availableLangs[]=$lang;

						$arrTmp[$article['article_id']] ['published'] = $article['global_published'];
						$arrTmp[$article['article_id']] ['creation_date'] = $article['global_creation_date'];
						$arrTmp[$article['article_id']] ['article_id'] = $article['article_id'];
						$arrTmp[$article['article_id']] ['key'] = $article['key'];

						$arrTmp[$article['article_id']] ['details'] [$lang] = Array(
						"id"=>$article['id'],
						"published"=>$article['published'],
						"published_on"=>$article['published_on'],
						"creation_date"=>$article['creation_date'],
						"alias"=>$article['alias'],
						"title"=>$article['title'],
						);
					} else {
						//$arrTmp[$article['article_id']] ['details'] [$lang] = Array();
						//if(!in_array($lang,$arrTmp[$article['article_id']] ['missing_languages']))
						//{
							//$arrTmp[$article['article_id']] ['missing_languages'] [] = $lang;
						//}

					}
				}


				/*
				foreach($arrLang as $lang)
				{
					if( !in_array($lang,$availableLangs) )
					{
						$arrTmp[$article['article_id']] ['missing_languages'] []  = $lang;
					}
				}
				unset($availableLangs);
				*/

			}

			foreach($arrTmp as $article_id=>$article)
			{
				foreach($arrLang as $lang)
				{
					if( isset($article ['details'] [$lang]) )
					{

					} else {
						$arrTmp[$article_id]['missing_languages'][]=$lang;
					}
				}
			}

			$this->view->list = $arrTmp;


			//exit;
		} else {
			$this->view->list = Array();
		}


        // action body
    }

    public function addAction()
    {
		if(!hasAccess("acp_article")){
			$this->redirect($this->view->Url(Array(),"NoAccess",true));
		}
		$request = $this->getRequest();
		$obj_a=new Article_Model_Articles();
		$obj_ad=new Article_Model_ArticleDetails();
		//print_r($obj_a->fetchRow());
//		print_r($obj_ad->fetchRow()->toArray());
//		exit;

		$lang = $request->getParam("language","en");
		$article_id = $request->getParam("article_id",0);

		$form = new Article_Form_Article();

		$check_key = new Zend_Validate_Db_NoRecordExists(array('table' => "articles",'field' => 'key'));
		$check_key->setMessage("Key already exists!");




		$check_title = new Model_Validate_Db_NoRecordExists(
			Array(
				'table' => "article_details",
				'fields' => Array("title" ,"lang")
				));
		/*
		$check_title = new Zend_Validate_Db_NoRecordExists(
			Array(
				'table' => "article_details",
				'field' => "title",
				"exclude"=>Array(
					"field"=>"lang",
					"value"=>"en"
					)
				)
			);
		*/
		$check_title->setMessage("Title already exists!");

		if($article_id==0)
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

				if($data['article_id']==0)
				{

					$data = $form->getValues();
					$article = Array(
					"creation_date"=>time(),
					"created_by"=>$this->user_id,
					"published"=>$data['published'],
					"published_by"=>$data['author'],
					"published_on"=>$published_on->get(Zend_Date::TIMESTAMP),
					"key"=>$data['key'],
					);
					try
					{
						$article_id = $obj_a->insert($article);
					}
					catch(EXCEPTION $e)
					{
						echo $e;
						$form->populate($data);
					}

				}
				else
				{
					$article_id=$data['article_id'];
				}



				if($article_id>0)
				{
					if($data['image_url']<>"")
					{
						$image = $data['image_url'];
					} else {
						$image="";
					}

					if($data['image_url']<>"")
					{
						$image = $data['image_url'];
					} else {
						$image="";
					}

					$articleDetails = Array(
					"article_id"=>$article_id,
					"lang"=>$data['lang'],
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
					"image"=>$image,
					);

					try
					{
						if( $obj_ad->insert($articleDetails) )
						{

							// Upload image

							if( $form->image->isUploaded())
							{
								$pathinfo = pathinfo($form->image->getFileName());
								$ext = strtolower( $pathinfo['extension'] );

								$destination = APPLICATION_PATH."/../media/article/".$article_id."/";

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

							header("Location: ".$this->view->url( Array("module"=>"article","controller"=>"admin","action"=>"index"),"",true ));
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
			if($article_id>0)
			{
				$article = $obj_a->find($article_id);

				if($article)
				{
					$form->key->setValue($article[0]['key']);
					$form->key->setAttrib('readonly', 'readonly');
				}
				else
				{
					exit("ERR:001");
				}
			}



			$date = new Zend_Date();
			$form->published_on->setValue($date->toString("YYYY-MM-dd"));

			$form->lang->setValue($lang);
			$form->article_id->setValue($article_id);
		}

		$form->alias->setAttrib('readonly', 'readonly');
		$form->alias->setRequired(false);
		$form->alias->setValue($this->view->translate("will_filled_automatically"));


		$this->view->form = $form;


    }

	public function editAction()
	{
		if(!hasAccess("acp_article")){
			$this->redirect($this->view->Url(Array(),"NoAccess",true));
		}
		$request = $this->getRequest();
		$obj_a=new Article_Model_Articles();
		$obj_ad=new Article_Model_ArticleDetails();

		$lang = $request->getParam("language","en");
		$article_id = $request->getParam("article_id",0);

		$form = new Article_Form_Article();

		$regex = new Zend_Validate_Regex('/^[a-zA-Z0-9\-_]*$/');
		$regex->setMessage("wrong_characters");
		$form->alias->addValidator($regex);

		$check_alias = new Model_Validate_Db_NoRecordExists(
			Array(
				'table' => "article_details",
				'fields' => Array("alias" ,"lang"),
				"exclude"=>Array(
					"field"=>"article_id",
					"value"=>$article_id
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

				if($data['article_id'] > 0)
				{
					if($data['image_url']<>"")
					{
						$image = $data['image_url'];
					} else {
						$image="";
					}


					$articleDetails = Array(
					"article_id"=>$article_id,
					"lang"=>$data['lang'],
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
					"image"=>$image
					);

					try
					{
						if( $obj_ad->update ($articleDetails, "article_id=".$article_id." and lang='".$lang."'") )
						{

							// Upload image

							if( $form->image->isUploaded())
							{

								$pathinfo = pathinfo($form->image->getFileName());
								$ext = strtolower( $pathinfo['extension'] );

								$destination = APPLICATION_PATH."/../media/article/".$article_id."/";

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

							header("Location: ".$this->view->url( Array("module"=>"article","controller"=>"admin","action"=>"index"),"",true ));
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
			if($article_id>0 && $lang<>"")
			{
				$article = $obj_a->find($article_id);
				if($article)
				{
					$published_on = new Zend_Date ( $data['published_on'] );

					$data = $obj_ad->fetchRow("article_id=".$article_id." and lang='".$lang."'");
					if($data)
					{
						$published_on = new Zend_Date($data['published_on']);

						$articleDetails = Array(
						"article_id"=>$article_id,
						"alias"=>$data['alias'],
						"image_url"=>$data['image'],
						"lang"=>$data['lang'],
						"title"=>$data['title'],
						"content"=>$data['content'],
						"header"=>$data['header'],
						"page_title"=>$data['page_title'],
						"meta_keywords"=>$data['meta_keywords'],
						"meta_description"=>$data['meta_description'],
						"published"=>$data['published'],
						"published_on"=>$published_on->get("YYYY-MM-dd"),
						"author"=>$data["published_by"],
						"key"=>$article[0]['key'],
						);

						$form->populate($articleDetails);

						$form->key->setValue($article[0]['key']);
						$form->key->setAttrib('readonly', 'readonly');
						$form->lang->setValue($lang);
						$form->article_id->setValue($article_id);

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

	}

	public function delAction()
	{
		$this->_helper->layout->disableLayout();
		if(!hasAccess("acp_article_delete")){
			echo $this->view->Notification('notice_permission_denied',true);
			exit;
		}
		$request = $this->getRequest();

		$lang = $request->getParam("language","");
		$article_id = $request->getParam("article_id","");
		$a = $request->getParam("a","");


		if($article_id<>"" && $lang<>"")
		{
			$obj_ad = new Article_Model_ArticleDetails();
			$select = $obj_ad->select();
			$select->where("article_id=?",$article_id);
			$select->where("lang=?",$lang);

			$article = $obj_ad->fetchRow($select);

			if($article)
			{
				$this->view->lang = $lang;
				$this->view->title = $article->title;

				if($a == "y")
				{
					$obj_ad->delete("article_id='".$article_id."' and lang='".$lang."'");

					// delete article basic infos if no other language available
					$obj_ad = new Article_Model_ArticleDetails();
					$select = $obj_ad->select();
					$select->where("article_id=?",$article_id);
					$articles = $obj_ad->fetchAll($select);
					if($articles->count()==0)
					{
						$obj_a = new Article_Model_Articles();
						$obj_a->delete("article_id=".$article_id);
					}

					header("Location: ".$this->view->url( Array("module"=>"article","controller"=>"admin","action"=>"index"),"",true ));
					exit;
				}
			} else {
				header("Location: ".$this->view->url( Array("module"=>"article","controller"=>"admin","action"=>"index"),"",true ));
				exit;
			}
		} else {
			header("Location: ".$this->view->url( Array("module"=>"article","controller"=>"admin","action"=>"index"),"",true ));
			exit;
		}

	}

	public function publishAction()
	{
		if(!hasAccess("acp_article_publish")){
			echo $this->view->Notification('notice_permission_denied',true);
			exit;
		}
		$this->_helper->layout->disableLayout();

		$request = $this->getRequest();

		$lang = $request->getParam("language",0);
		$article_id = $request->getParam("article_id",0);

		$this->view->article_id = $article_id;
		$this->view->lang = $lang;

		if($article_id>0)
		{
			if($lang <> "0")
			{
				$obj_ad = new Article_Model_ArticleDetails();

				$articleDetail = $obj_ad->fetchRow("article_id=".$article_id." and lang = '".$lang."'");

				if($articleDetail)
				{
					$arrData = Array(
					"published"=>($articleDetail->published? 0:1),
					"changed_by"=>$this->user_id,
					"change_date"=>time()
					);
					$obj_ad->update($arrData,"article_id=".$article_id." and lang='".$lang."'");
					//header("Location: ".$this->view->url( Array("module"=>"article","controller"=>"admin","action"=>"index"),"",true ));
					$this->view->published = ($articleDetail->published? 0:1);
				}
				else
				{
					//header("Location: ".$this->view->url( Array("module"=>"article","controller"=>"admin","action"=>"index"),"",true ));
					//exit;
					$this->view->error=true;
				}
			}
			else
			{
				$obj_a = new Article_Model_Articles();
				$article = $obj_a->fetchRow("article_id=".$article_id);

				if($article)
				{
					$arrData = Array(
					"published"=>($article->published? 0:1),
					//"changed_by"=>$this->user_id,
					//"change_date"=>time()
					);
					$obj_a->update($arrData,"article_id=".$article_id);
					$this->view->published = ($article->published? 0:1);
					//header("Location: ".$this->view->url( Array("module"=>"article","controller"=>"admin","action"=>"index"),"",true ));
				}
				else
				{
					//header("Location: ".$this->view->url( Array("module"=>"article","controller"=>"admin","action"=>"index"),"",true ));
					//exit;
					$this->view->error=true;
				}
			}
		} else {
			//header("Location: ".$this->view->url( Array("module"=>"article","controller"=>"admin","action"=>"index"),"",true ));
			//exit;
			$this->view->error=true;
		}

	}

}