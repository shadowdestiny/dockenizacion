<?php

class Admin_Log_SystemController extends Zend_Controller_Action
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
		$searchParams = Array('priority' => -1);


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
		$obj_log = new Default_Model_Log_System();
		$select = $obj_log->select();
		$select->order("id desc");

		/** get the levels **/

		$cache = Zend_Registry::get("Zend_Cache");
		$cache_id = "log_settings_levels";

		if($cache->test($cache_id)){
			$arrLevels = $cache->load($cache_id);
		} else {
			$levels = $obj_log->select();
			$levels->group("level");
			$levels->order("priority asc");
			$tmpl = $obj_log->fetchAll($levels)->toArray();

			foreach($tmpl as $level){
				$arrLevels[$level['priority']] = '> ' . $level['level'];
			}
			$cache->save($arrLevels,$cache_id,Array($cache_id),180);
		}
/** Boingflip searchParams **/

		$this->view->searchParams = $searchParams;
		$this->view->priority = $arrLevels;

		/** Filter Options here **/
		if(!empty($searchParams['priority'])){
			$select->where('priority >= ?', $searchParams['priority']);
		}

		$s_count = clone($select);
		$s_count->from('log_system','COUNT(*) AS c');
		$count = $obj_log->fetchRow($s_count)->toArray();
		$max = $count['c'];

		/** info debug error **/
		$select->limitPage($page,$itemsperpage);
		$data = $obj_log->fetchAll($select)->toArray();

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
			$obj_log = new Default_Model_Log_System();
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