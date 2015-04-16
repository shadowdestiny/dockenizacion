<?php

class Translation_AdminController extends Zend_Controller_Action {

    public function init()
	{
		if(!hasAccess("acp") || !hasAccess("acp_translation"))
		{
			$this->redirect($this->view->Url(Array(),"NoAccess",true));
		}
    }

    public function indexAction() {

	$request = $this->getRequest();

	$arrLang = getAllLanguages("backend");
	$this->view->arrLang = $arrLang;

	$session = new Zend_Session_Namespace("translation");
	if ($session->fromLang <> "") {
	    $fromLang = $session->fromLang;
	    //exit("A");
	} else {
	    $fromLang = $arrLang[0];
	    //exit("B");
	}

	if ($session->toLang <> "") {
	    $toLang = $session->toLang;
	    //exit("A");
	} else {
	    $toLang = $arrLang[1];
	    //exit("B");
	}

	if ($session->search <> "") {
	    $search = $session->search;
	    //exit("A");
	} else {
	    $search = "";
	    //exit("B");
	}

	if ($session->type <> "") {
	    $type = $session->type;
	    //exit("A");
	} else {
	    $type = "all";
	    //exit("B");
	}

	if ($session->status <> "") {
	    $status = $session->status;
	    //exit("A");
	} else {
	    $status = "1";
	    //exit("B");
	}


	$language1 = $request->getParam("language1", $fromLang);
	$status = $request->getParam("status", $status);
	$language2 = $request->getParam("language2", $toLang);
	$search = $request->getParam("search", $search);
	$type = $request->getParam("type", $type);

	$session->fromLang = $language1;
	$session->toLang = $language2;
	$session->search = $search;
	$session->type = $type;
	$session->status = $status;

	$this->view->language1 = $language1;
	$this->view->language2 = $language2;
	$this->view->type = $type;
	$this->view->search = $search;
	$this->view->status = $status;

//		echo $search;
//		exit;

	$obj_t = new Default_Model_Translations();
	$obj_td = new Default_Model_TranslationDetails();

	$select = $obj_t->getAdapter()->select();
	$select->from(Array("t" => "translations"), Array("*"));
	$select->join(Array("td" => "translation_details"), "td.translation_id = t.translation_id", Array("*"));

	if ($type == "missing") {
	    $select->where("t.translation_id not in ( select translation_id from translation_details where lang='" . $language2 . "' and value<>'' )");
	}

	if ($search <> "") {
	    $selectAnd1 = $obj_t->select()
		    ->where("td.value like (?)", "%" . $search . "%")
		    ->getPart(Zend_Db_Select::WHERE);

	    $selectAnd2 = $obj_t->select()
		    ->where("t.key like (?)", "%" . $search . "%")
		    ->getPart(Zend_Db_Select::WHERE);

	    $selectOr = $obj_t->select()
		    ->orWhere(implode(' ', $selectAnd1))
		    ->orWhere(implode(' ', $selectAnd2))
		    ->getPart(Zend_Db_Select::WHERE);

	    $select->where(implode(' ', $selectOr));
	}
	//$select->where("t.used=?", $status); //used or not
	//$select->where("t.used=?",1); //used or not
	$select->order("t.key");
	$list = $obj_t->getAdapter()->fetchAll($select);

	$arrTranslations = Array();

	if ($list) {
	    foreach ($list as $translation) {

			$trans_id = $translation['translation_id'];
			if (empty($arrTranslations[$trans_id][$language1])){$arrTranslations[$trans_id][$language1] = ''; }
			if (empty($arrTranslations[$trans_id][$language2])){$arrTranslations[$trans_id][$language2] = ''; }

			$arrTranslations[$trans_id]['translation_id'] = $trans_id;
			$arrTranslations[$trans_id]['key'] = $translation['key'];
			$lang = $translation['lang'];

			if ($language1 == $lang || $language2 == $lang) {
				$arrTranslations[$trans_id][$lang] = $translation['value'];
			}
	    }
	}

	$this->view->list = $arrTranslations;
    }

    public function editAction() {
	$obj_t = new Translation_Model_Translations();
	$obj_td = new Translation_Model_TranslationDetails();

	$form = new Translation_Form_Translation();
	$ajrDataRequest = false;



	$request = $this->getRequest();

	$translation_id = $request->getParam("translation_id", 0);

	$arrLang = getAllLanguages("backend");

	if ($request->isPost()) {
	    $cache = Zend_Registry::get("Zend_Cache");
	    $cache_key = "Translation";
	    $cache->remove($cache_key);

	    $data = $request->getPost();


	    if ($data['pk']) {
		$ajrDataRequest = true;
		$ajrData['translation_id'] = $data['pk'];
		$valname = $data['name'];
		$ajrData[$valname] = $data['value'];
		$data = $ajrData;
	    }


	    if ($form->isValid($data)) {
		foreach ($arrLang as $lang) {
		    if (isset($data["value_" . $lang])) {
			if ($data["value_" . $lang] <> "") {
			    $select = $obj_td->select();
			    $select->where("translation_id=?", $translation_id);
			    $select->where("lang=?", $lang);

			    $row = $obj_td->fetchRow($select);
			    if ($row) {
				if ($row->value <> $data["value_" . $lang]) {
				    $arrData = Array(
					"value" => $data['value_' . $lang]
				    );
				    $obj_td->update($arrData, "translation_id=" . $translation_id . " and lang='" . $lang . "'");
				}
			    } else {
				$arrData = Array(
				    "translation_id" => $translation_id,
				    "lang" => $lang,
				    "value" => $data['value_' . $lang],
				);
				$obj_td->insert($arrData);
			    }
			} else {
			    // if exists, delete this translation
			    $obj_td->delete("translation_id=" . $translation_id . " and lang='" . $lang . "'");
			}
		    }
		}

		if ($ajrDataRequest) {
		    echo json_encode(Array("success" => true));
		    exit;
		} else {
		    header("Location: " . $this->view->url(Array("module" => "translation", "controller" => "admin", "action" => "index"), "", true));
		    exit;
		}
	    } else {
		$form->populate($data);
	    }
	} else {
	    if ($translation_id > 0) {
		$tag = $obj_t->find($translation_id);
		if ($tag) {
		    $form->key->setValue($tag[0]['key']);
		    $form->translation_id->setValue($translation_id);
		    $form->key->setAttrib('readonly', 'readonly');


		    $data = $obj_td->fetchAll("translation_id=" . $translation_id);

		    if ($data->count() > 0) {
			foreach ($data as $item) {
			    if (in_array($item->lang, $arrLang)) {
				$form->{value . "_" . $item->lang}->setValue($item->value);
			    }
			}
		    }
		} else {
		    exit("E003");
		}
	    } else {
		exit;
	    }
	}

	$this->view->form = $form;
    }

    public function addAction() {
	$obj_t = new Translation_Model_Translations();
	$obj_td = new Translation_Model_TranslationDetails();

	$form = new Translation_Form_Translation();

	$check_key = new Zend_Validate_Db_NoRecordExists(array('table' => "translations", 'field' => 'key'));
	$check_key->setMessage("Key already exists!");
	$form->key->addValidator($check_key);

	$form->value_en->setRequired(true);

	$request = $this->getRequest();

	if ($request->isPost()) {
	    $cache = Zend_Registry::get("Zend_Cache");
	    $cache_key = "Translation";
	    $cache->remove($cache_key);

	    $data = $request->getPost();

	    if ($form->isValid($data)) {
		$arrData = Array(
		    "key" => $data['key']
		);

		$translation_id = $obj_t->insert($arrData);

		if ($translation_id > 0) {
		    $arrLang = getAllLanguages("backend");

		    foreach ($arrLang as $lang) {
			if ($data['value_' . $lang] <> "") {
			    $arrData = Array(
				"translation_id" => $translation_id,
				"lang" => $lang,
				"value" => $data['value_' . $lang]
			    );
			    try {
				$obj_td->insert($arrData);
			    } catch (EXCEPTION $e) {

			    }
			}
		    }
		} else {
		    exit("ERR003");
		}
		$this->redirect($this->view->url(Array("module" => "translation", "controller" => "admin", "action" => "add"), "", true));
		exit;
	    } else {
		$form->populate($data);
	    }
	}

	$this->view->form = $form;
    }

    public function delAction() {
	$obj_t = new Translation_Model_Translations();
	$obj_td = new Translation_Model_TranslationDetails();

	$this->_helper->layout->disableLayout();

	$request = $this->getRequest();

	$translation_id = $request->getParam("translation_id", "");
	$a = $request->getParam("a", "");

	$this->view->translation_id = $translation_id;


	if ($translation_id <> "") {
	    $select = $obj_t->select();
	    $select->where("translation_id=?", $translation_id);

	    $translation = $obj_t->fetchRow($select);

	    if ($translation) {
		$this->view->key = $translation->key;

		if ($a == "y") {
		    $obj_td->delete("translation_id=" . $translation_id);
		    $obj_t->delete("translation_id=" . $translation_id);

		    $cache = Zend_Registry::get("Zend_Cache");
		    $cache_key = "Translation";
		    $cache->remove($cache_key);

		    echo json_encode(Array("success" => true));
		    //header("Location: ".$this->view->url( Array("module"=>"translation","controller"=>"admin","action"=>"index"),"",true ));
		    exit;
		}
	    } else {
		header("Location: " . $this->view->url(Array("module" => "translation", "controller" => "admin", "action" => "index"), "", true));
		exit;
	    }
	} else {
	    header("Location: " . $this->view->url(Array("module" => "translation", "controller" => "admin", "action" => "index"), "", true));
	    exit;
	}
    }

}
