<?php

class Admin_CountryController extends Zend_Controller_Action
{

    public function init()
    {
        if(!hasAccess("acp") || !hasAccess("acp_default_country"))
		{
			$this->redirect($this->view->Url(Array(),"NoAccess",true));
		}
    }

    public function indexAction()
    {
        // countrylist
		$obj_c = new Default_Model_Countries();

		$select = $obj_c->select();
		$select->from('countries');
		$select->order("name");

		$arrCountries = $obj_c->getAdapter()->fetchAll($select);
		if($arrCountries){
			$this->view->countries = $arrCountries;
		}

    }

    public function editAction()
    {
		$request = $this->getRequest();

		$obj_c = new Default_Model_Countries();
		$cid = $request->getParam('country_id',false);

		if($cid){
			$select = $obj_c->select();
			$select->from('countries');
			$select->where("country_id = ?",$cid);
			$this->view->country = $obj_c->fetchRow($select)->toArray();
		} else {
			exit('no id');
		}

		$dataPK = $request->getParam('pk',false);

		if ($request->isPost() || $dataPK) {
			$arrUpdate = array();

			if($dataPK){ //live edit
				$data = $request->getParams();
				if($dataPK == 'status'){
					$curStatus = $this->view->country[$data['name']];
					if($curStatus == 0) { $data['value'] = '1'; } else {$data['value']= '0';}
				}
				$arrUpdate[$data['name']] = $data['value'];
			} else {
				$arrUpdate = $request->getPost();
			}

			$status = $obj_c->update($arrUpdate,"country_id=".$cid);

			if($status){
				$cache = Zend_Registry::get("Zend_Cache");
				$cache->remove("Country_List_frontend");
				$cache->remove("Country_List_backend");
			}

			if ($dataPK && $dataPK == 'status'){
				if($status == 0){$this->view->error = true;}
				$this->view->country_id = $cid;
				$this->view->published = $data['value'];
				$this->view->type = $data['name'];
				echo $this->view->render('/default/admin/country/publish.phtml');
				exit();
			}

		}

    }

}