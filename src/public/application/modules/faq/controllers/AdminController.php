<?php

class Faq_AdminController extends Zend_Controller_Action
{

	protected $user_id=0;

    public function init()
    {
		if(!hasAccess("acp") || !hasAccess("acp_faq"))
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

	public function indexAction(){

		$request = $this->getRequest();

		$obj_f = new Faq_Model_Faq();
		$obj_fd = new Faq_Model_Faq_Data();

		$select = $obj_f->getAdapter()->select();

		$select->from(Array("f"=>"faqs"),Array("*","global_published"=>"f.active","global_creation_date"=>"f.creation_date"));
		$select->join(Array("fd"=>"faq_data"),"fd.faq_id = f.faq_id",Array("*"));

		$select->order("f.pos");

		$faqList = $obj_f->getAdapter()->fetchAll($select);

		$arrLang = getAllLanguages("backend");

		$this->view->arrLang = $arrLang;


		if($faqList)
		{

			foreach($faqList as $faq)
			{
				$availableLangss=Array();

				foreach($arrLang as $lang)
				{

					if($faq['lang'] == $lang)
					{
						$availableLangs[]=$lang;

						$arrTmp[$faq['category']][$faq['faq_id']] ['published'] = $faq['global_published'];
						$arrTmp[$faq['category']][$faq['faq_id']] ['creation_date'] = $faq['global_creation_date'];
						$arrTmp[$faq['category']][$faq['faq_id']] ['faq_id'] = $faq['faq_id'];
						$arrTmp[$faq['category']][$faq['faq_id']] ['category'] = $faq['category'];

						$arrTmp[$faq['category']][$faq['faq_id']] ['details'] [$lang] = Array(
						"question"=>$faq['question'],
						"answer"=>$faq['answer'],
						);
					}
				}
			}

			$current_language = Settings::get('S_lang');
			foreach($arrTmp as $category => $arrCat){
				foreach($arrCat as $faq_id=>$faq)
				{

					if (isset($faq['details'][$current_language])){
						$arrTmp[$category][$faq_id]['title'] = $faq['details'][$current_language]['question'];
					}

					foreach($arrLang as $lang)
					{
						if( isset($faq['details'][$lang]) )
						{

						} else {
							$arrTmp[$category][$faq_id]['missing_languages'][]=$lang;
						}
					}
				}
			}
			$this->view->list = $arrTmp;


			//exit;
		} else {
			$this->view->list = Array();
		}

	}

	public function addAction(){
		if(!hasAccess("acp_faq_edit")){
			$this->redirect($this->view->Url(Array(),"NoAccess",true));
		}
		$obj_f = new Faq_Model_Faq();
		$obj_fd = new Faq_Model_Faq_Data();

		$form = new Faq_Form_Faq();

		$check_key = new Zend_Validate_Db_NoRecordExists(array('table' => "faqs",'field' => 'name'));
		$check_key->setMessage("Name already exists!");
		$form->name->addValidator($check_key);

		$form->question_en->setRequired(true);
		$form->answer_en->setRequired(true);

		$request = $this->getRequest();

		if($request->isPost())
		{
			$data = $request->getPost();
			if($form->isValid($data))
			{
				$select = $obj_f->select();
				$select->from(Array("faqs"),Array("position" => "max(pos)"));

				$faqMax = $obj_f->fetchRow($select);
				if ($faqMax->position > 0){
					$pos = $faqMax->position + 1;
				} else {
					$pos = 0;
				}

				$arrData = Array(
					"active"=>$data['active'],
					"pos"=>$pos,
					"category"=>$data['category'],
					"name"=>$data['name'],
					"created_by" => $this->user_id,
					"changed_by" => $this->user_id,
					"creation_date" => Zend_Date::now()->toString('yyyyMMddHHmmss'),
					"change_date" => Zend_Date::now()->toString('yyyyMMddHHmmss')
				);

				$faq_id = $obj_f->insert($arrData);
				$this->updatePositions();

				if($faq_id>0)
				{

					$arrLang = getAllLanguages("backend");

					foreach($arrLang as $lang)
					{
						if( $data['question_'.$lang] <>"")
						{
							$arrData=Array(
							"faq_id"=>$faq_id,
							"lang"=>$lang,
							"question"=>$data['question_'.$lang],
							"answer"=>$data['answer_'.$lang],
							"change_date" => Zend_Date::now()->toString('yyyyMMddHHmmss'),
							"changed_by" => $this->user_id,
							);
							try
							{
								$obj_fd->insert($arrData);
							}
							catch(EXCEPTION $e)
							{

							}
						}
					}
				} else {
					exit("ERR003");
				}
				$this->redirect($this->view->url( Array("module"=>"faq","controller"=>"admin","action"=>"index"),"",true ));
				exit;
			}
			else
			{
				$form->populate($data);
			}

		}

		$this->view->form = $form;
	}

	public function editAction(){
		if(!hasAccess("acp_faq_edit")){
			$this->redirect($this->view->Url(Array(),"NoAccess",true));
		}
		$obj_f = new Faq_Model_Faq();
		$obj_fd = new Faq_Model_Faq_Data();

		$form = new Faq_Form_Faq();
		$request = $this->getRequest();

		$faq_id = $request->getParam("faq_id",0);
		$arrLang = getAllLanguages("backend");
		$form->name->setAttrib('readonly', 'readonly');

		if($request->isPost()) {
			$data = $request->getPost();

			if($form->isValid($data)){

				$arrMainData =  Array(
					"active" => $data['active'],
					"category" => $data['category'],
					"change_date" => Zend_Date::now()->toString('yyyyMMddHHmmss'),
					"changed_by" => $this->user_id
				);

				$obj_f->update($arrMainData,"faq_id=".$faq_id);

				foreach($arrLang as $lang)
				{
					if( $data["question_".$lang]<>"" )
					{
						$select = $obj_fd->select()						;
						$select->where("faq_id=?",$faq_id);
						$select->where("lang=?",$lang);

						$row = $obj_fd->fetchRow($select);

						if($row)
						{
							if($row->question <> $data["question_".$lang] || $row->answer <> $data["answer_".$lang])
							{
								$arrData = Array(
									"question" => $data['question_'.$lang],
									"answer" => $data['answer_'.$lang],
									"change_date" => Zend_Date::now()->toString('yyyyMMddHHmmss'),
									"changed_by" => $this->user_id
								);
								$obj_fd->update($arrData,"faq_id=".$faq_id." and lang='".$lang."'");
							}
						} else {
							$arrData = Array(
								"faq_id"=>$faq_id,
								"lang"=>$lang,
								"question"=>$data['question_'.$lang],
								"answer"=>$data['answer_'.$lang],
								"change_date" => Zend_Date::now()->toString('yyyyMMddHHmmss'),
								"changed_by" => $this->user_id
							);
							$obj_fd->insert($arrData);
							$this->updatePositions();
						}
					}
				}
			} else {
				$form->populate($data);
			}
		} else {
			if($faq_id>0)
			{
				$faq = $obj_f->find($faq_id);
				if($faq) {
					$form->name->setValue($faq[0]['name']);
					$form->active->setValue($faq[0]['active']);
					$form->category->setValue($faq[0]['category']);
					$form->faq_id->setValue($faq_id);

					$data = $obj_fd->fetchAll("faq_id=".$faq_id);

					if($data->count()>0)
					{
						foreach($data as $item)
						{
							if(in_array($item->lang,$arrLang))
							{
								$form->{"question_".$item->lang}->setValue($item->question);
								$form->{"answer_".$item->lang}->setValue($item->answer);
							}
						}
					}
				} else {
					exit("E003");
				}
			}
			else
			{
				exit;
			}



		}
		$this->view->form = $form;

	}

	public function delAction(){
		if(!hasAccess("acp_faq_delete")){
			$this->redirect($this->view->Url(Array(),"NoAccess",true));
		}
		$obj_f = new Faq_Model_Faq();
		$obj_fd = new Faq_Model_Faq_Data();

		$this->_helper->layout->disableLayout();

		$request = $this->getRequest();

		$faq_id = $request->getParam("faq_id","");
		$a = $request->getParam("a",""); //action

		$this->view->faq_id = $faq_id;


		if($faq_id<>""){
			$select = $obj_f->select();
			$select->where("faq_id=?",$faq_id);

			$faqs = $obj_f->fetchRow($select);

			if($faqs)
			{
				$this->view->name = $faqs->name;

				if($a == "y")
				{
					$obj_fd->delete("faq_id=".$faq_id);
					$obj_f->delete("faq_id=".$faq_id);

					$this->updatePositions();

					echo json_encode(Array( "success"=>true ));
					//header("Location: ".$this->view->url( Array("module"=>"translation","controller"=>"admin","action"=>"index"),"",true ));
					exit;
				}
			} else {
				header("Location: ".$this->view->url( Array("module"=>"faq","controller"=>"admin","action"=>"index"),"",true ));
				exit;
			}
		} else {
			header("Location: ".$this->view->url( Array("module"=>"faq","controller"=>"admin","action"=>"index"),"",true ));
			exit;
		}

	}


	public function publishAction()
	{
		if(!hasAccess("acp_faq_publish")){
			$this->redirect($this->view->Url(Array(),"NoAccess",true));
		}

	}

	public function positionAction()
	{
		if(!hasAccess("acp_faq_publish")){
			exit();
		}
		$request = $this->getRequest();

		$arrFaq = $request->getPost();

		$sort = $request->getParam("sort",false); //action
		if ($sort && !empty($arrFaq['data'])){


			$obj_f = new Faq_Model_Faq();

			$select = $obj_f->select();
			$select->group('category');
			$cats = $obj_f->fetchAll($select);

			//print_r($arrFaq);exit;

			if ($cats->count() > 0){

				foreach($cats as $cat){
								//		print_r($cat->category . $arrFaq['data'][$cat->category]);
					foreach ($arrFaq['data'][$cat->category] as $faq_id => $pos){
						$arrData =  Array(
							"pos" => $pos,
							"category" => $cat->category,
							"change_date" => Zend_Date::now()->toString('yyyyMMddHHmmss'),
							"changed_by" => $this->user_id
						);
						$obj_f->update($arrData,"faq_id=".$faq_id);

					}
				}
				$this->updatePositions();
			}

		} else {
			//do some stuff

		}

	}


	private function updatePositions()
	{
		$obj_f = new Faq_Model_Faq();

		$select_cats = $obj_f->select();
		$select_cats->group('category');

		$objCats = $obj_f->fetchAll($select_cats);

		if ($objCats->count() > 0){
			foreach($objCats as $cat){

				$category = $cat->category;
				$select = $obj_f->select();
				$select->where("category=?",$category);
				$select->order(array('pos ASC'));

				$objFaqs = $obj_f->fetchAll($select);
				$pos = 1;
				foreach($objFaqs as $faq){
					if ($faq->pos != $pos){
						$arrData =  Array(
							"pos" => $pos,
						);
						$obj_f->update($arrData,"faq_id=".$faq->faq_id);
					}
					$pos++;


				}
				$pos = 1;

			}
		}
		exit();
	}

}