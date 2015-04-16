<?php

class Lottery_Admin_Euromillions_Log_LCController extends Zend_Controller_Action
{

    public function init()
    {
		if(!hasAccess("acp") || !hasAccess("acp_log") || !hasAccess("acp_lottery"))
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
		$searchParams = Array('status' => '');

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
		/** lc_id = Ticket id
		 *  ticket_id
		 *
		 * intern
		 * / user_id /order_id / em_ticket_id
		 */
		/** get Data **/
		$obj_log = new Lottery_Model_Lc_Log();
		$select = $obj_log->select();
		$select->order("id desc");

		/** get the Status types **/
		$cache = Zend_Registry::get("Zend_Cache");
		$cache_id = "log_lc_status";

		if($cache->test($cache_id)){
			$arrStatus = $cache->load($cache_id);
		} else {
			$status = $obj_log->select();
			$status->group("status");
			$status->order("status asc");
			$tmpl = $obj_log->fetchAll($status)->toArray();

			foreach($tmpl as $status){
				$arrStatus[$status['status']] = $status['status'];
			}
			$cache->save($arrStatus,$cache_id,Array($cache_id),180);
		}


		/** Boingflip searchParams **/
		$this->view->searchParams = $searchParams;
		$this->view->statustypes = $arrStatus;

		/** Filter Options here **/
		if(!empty($searchParams['status'])){
			$select->where('status = ?', $searchParams['status']);
		}

		if(!empty($searchParams['intern_id'])){
			$select->where('user_id LIKE ? OR order_id LIKE ? OR em_ticket_id LIKE ?',  $searchParams['intern_id']);
		}

		if(!empty($searchParams['extern_id'])){
			$select->where('lc_id LIKE ? OR ticket_id LIKE ?',  $searchParams['extern_id']);
		}

		$s_count = clone($select);
		$s_count->from('lc_log','COUNT(*) AS c');
		$count = $obj_log->fetchRow($s_count)->toArray();
		$max = $count['c'];


		/** info debug error **/
		$select->limitPage($page,$itemsperpage);
		$data = $obj_log->fetchAll($select)->toArray();
		$paginator_array = Array();
		if($max > 0){
			$paginator_array = array_fill(0, $max, '');
		}


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
			$obj_log = new Lottery_Model_Lc_Log();
			$select = $obj_log->select();
			$select->where('id = ?', $id);
			$data = $obj_log->fetchRow($select);
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