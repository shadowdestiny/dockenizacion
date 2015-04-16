<?php

class Admin_CurrencyController extends Zend_Controller_Action
{

    public function init()
    {
        if(!hasAccess("acp") || !hasAccess("acp_default_currency"))
		{
			$this->redirect($this->view->Url(Array(),"NoAccess",true));
		}
    }

    public function indexAction()
    {
        // countrylist
		$obj_c = new Default_Model_Currencies();
		$select = $obj_c->select();
		$select->order("currency");

		$arrCurrencies = $obj_c->getAdapter()->fetchAll($select);

		if($arrCurrencies){
			$this->view->currencies = $arrCurrencies;
		}

    }

    public function editAction()
    {
		$request = $this->getRequest();

		$obj_c = new Default_Model_Currencies();
		$cid = $request->getParam('id',false);

		if($cid){
			$select = $obj_c->select();
			$select->where("id = ?",$cid);
			$this->view->currency = $obj_c->fetchRow($select)->toArray();

			$dataPK = $request->getParam('pk',false);
			$arrUpdate = array();
			$data = $request->getParams();

			if($dataPK){ //live edit
				if($dataPK == 'status'){
					$curStatus = $this->view->currency[$data['name']];
					$data['value'] = ($curStatus == 0) ? '1' : '0';
				}
				$arrUpdate[$data['name']] = $data['value'];
			} else {
				if(isset($data['name'])){
					$arrUpdate[$data['name']] = $data['value'];
				} else {
					echo Zend_Json::encode(Array('error'=>true, 'value' => 'No Status'));exit;
				}
			}

			$arrUpdate['last_update'] = Zend_Date::now()->toString('yyyy-MM-dd HH:mm:ss');
			/** Validation **/
			if(isset($arrUpdate['rate'])){
				if(!is_numeric($arrUpdate['rate'])){
					echo Zend_Json::encode(Array('error'=>true, 'value' => 'Not a Number'));exit;
				}
			}
			$updt = $obj_c->update($arrUpdate,"id=".$cid);

			if($updt){
				$arrResponse['last_update'] = $arrUpdate['last_update'];
				$cache = Zend_Registry::get("Zend_Cache");
				$cache->remove("Currency_List");
			} else {
				echo Zend_Json::encode(Array('error'=>true, 'value' => 'Update Fails'));exit;
			}

			if ($dataPK && $dataPK == 'status'){
				if($updt == 0){$this->view->error = true;}
				$this->view->id = $cid;
				$this->view->published = $data['value'];
				$this->view->type = $data['name'];
				echo $this->view->render('/default/admin/currency/publish.phtml');
				exit();
			} else {
				echo Zend_Json::encode(Array('success'=>true,'id' => $cid, 'data'=> $arrResponse));exit;
			}
		} else {
			echo Zend_Json::encode(Array('error'=>true, 'value' => 'No ID Given'));exit;
		}
    }

}