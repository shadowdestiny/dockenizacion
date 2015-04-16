<?php

class Admin_Log_TransactionsController extends Zend_Controller_Action
{

    public function init()
    {
		if(!hasAccess("acp") || !hasAccess("acp_log"))
		{
			$this->redirect($this->view->Url(Array(),"NoAccess",true));
		}
		//@Micha check mal den scheiß
    }

	public function indexAction()
	{
		$request = $this->getRequest();
		$page = $request->getParam("page",0);
		$params = $request->getParams();
		$itemsperpage = 50;
		$searchParams = Array('transaction_type' => '');

		if($request->isPost()) {
			$params['page'] = $page;
			if(!empty($params)){
				foreach($params as $param => $value){
					if($value <> '' && $value != '-1'){
						$urlp[$param] = urlencode($value);
					}
				}
				$url = $this->view->url($urlp,NULL,true);
				$this->_redirect($url);
			}
		} else {
			unset($params['module'],$params['controller'],$params['action'],$params['lang']);
			if(!empty($params)){
				foreach($params as $key => $param){
					$searchParams[$key] = urldecode($param);
				}
			}
		}

		/** get Data **/
		$obj_t = new Billing_Model_Transactions();
		$select = $obj_t->select();
		$select->order("transaction_id desc");

		/** get the levels **/
		$cache = Zend_Registry::get("Zend_Cache");
		$cache_id = "log_transactions_types";

		if($cache->test($cache_id)){
			$arrTypes = $cache->load($cache_id);
		} else {
			$types = $obj_t->select();
			$types->group("transaction_type");
			$types->order("transaction_type asc");
			$tmpl = $obj_t->fetchAll($types)->toArray();

			foreach($tmpl as $type){
				$arrTypes[$type['transaction_type']] = $type['transaction_type'];
			}
			$cache->save($arrTypes,$cache_id,Array($cache_id),180);
		}

		/** Boingflip searchParams **/
		$this->view->searchParams = $searchParams;
		$this->view->transtypes = $arrTypes;

		/** Filter Options here **/
		if(!empty($searchParams['transaction_type'])){
			$select->where('level transaction_type ?', $searchParams['transaction_type']);
		}

		$s_count = clone($select);
		$s_count->from('transactions','COUNT(*) AS c');
		$count = $obj_t->fetchRow($s_count)->toArray();
		$max = $count['c'];

		/** info debug error **/
		$select->limitPage($page,$itemsperpage);
		$data = $obj_t->fetchAll($select)->toArray();

		$paginator_array = array_fill(0, $max, '');

		$pager = new Zend_Paginator(new Zend_Paginator_Adapter_Array($paginator_array));
		$pager->setCurrentPageNumber($page);
		$pager->setItemCountPerPage($itemsperpage);
		$this->view->paginator = $this->view->paginationControl(
			$pager,
			'Sliding',
			'default/pagination/pagination.phtml'
		);

		$this->view->items = $data;
	}


	public function detailsAction()
	{
		$request = $this->getRequest();
		$id = $request->getParam("id",0);

		$this->view->popup = $request->getParam("popup",false);
		if($this->view->popup ){
			$this->_helper->layout->disableLayout();
		}

		if ($id > 0){
			$obj_t = new Billing_Model_Transactions();
			$select = $obj_t->select();
			$select->where('transaction_id = ?', $id);
			$data = $obj_t->fetchRow($select);
			if($data){
				$data = $data->toArray();
				$success = true;
				$this->view->items = $data;
			} else {
				$this->view->error = true;
			}
		} else {
			$this->view->error = true;
		}
	}

}