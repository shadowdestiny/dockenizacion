<?php

class AdminstatsController extends Zend_Controller_Action
{
	public function init()
	{
		if(!hasAccess("acp_stats"))
		{
			$this->redirect($this->view->Url(Array(),"NoAccess",true));
		}
	}

	public function indexAction()
	{
		$request = $this->getRequest();
		$start = $request->getParam("start",0);
		$end = $request->getParam("end",0);
		$customer_id = $request->getParam("customer_id",0);

		if($start==0)
		{
			$start=new Zend_Date(null,"de_DE");
		} else {
			$start=new Zend_Date($start,"de_DE");
		}

		if($end==0)
		{
			$end=new Zend_Date(null,"de_DE");
		} else {
			$end=new Zend_Date($end,"de_DE");
		}



		$start->setHour(0);
		$start->setMinute(0);
		$start->setSecond(0);

		$end->setHour(23);
		$end->setMinute(0);
		$end->setSecond(0);

		//echo $start;
		//exit;

		$this->view->filter = Array(
		"start"=>$start->toString('yyyy-MM-dd'),
		"end"=>$end->toString('yyyy-MM-dd'),
		"customer_id"=>$customer_id
		);

		// get data
		$obj_s = new Default_Model_Stats();
		$data = $obj_s->getStatsData($start,$end,0);

		$display_type = $obj_s->getType($start->get(Zend_Date::TIMESTAMP),$end->get(Zend_Date::TIMESTAMP),0);

		$this->view->display_type = $display_type;
		$this->view->statsdata = $data;
		//echo $start;
		//exit;
		//print_r($data);
//		exit;
	}

	public function drawsAction(){
		$request = $this->getRequest();
		$lottery = $request->getParam("lottery","euromillions");
		$customer_id = $request->getParam("customer_id",0);

		if($lottery == 'euromillions'){


			$obj_t = new Default_Model_Stats_Ticket_Lc();
			$arrDraws = $obj_t->getDraws('',$customer_id,'group');

			// Paginator
			$page=$request->getParam('page');
			$paginator = Zend_Paginator::factory($arrDraws);
			$paginator->setItemCountPerPage(20);
			$paginator->setCurrentPageNumber($page);

			$this->view->pageCount = $paginator->getPages()->pageCount;
			$this->view->currentPage = $page;
			$this->view->paginator = $this->view->paginationControl(
				$paginator,
				'Sliding',
				'default/pagination/pagination.phtml'
			);

			$this->view->items = $paginator;

		} else {
			exit('wrong lottery');
		}
		$this->view->lottery = $lottery;

	}

	public function drawdetailsAction(){
		$this->_helper->layout->disableLayout();
		$request = $this->getRequest();
		$draw_id = $request->getParam("draw_id","");
		$customer_id = $request->getParam("customer_id","0");
		$lottery = $request->getParam("lottery","euromillions");

		if($draw_id){
			if($lottery == 'euromillions'){

				$obj_t = new Default_Model_Stats_Ticket_Lc();
				$arrDraws = $obj_t->getDraws($draw_id,$customer_id,'group');
				$arrProducts = $obj_t->getDraws($draw_id,$customer_id,'all');

				$this->view->details =  $arrDraws[0];
				$this->view->products = $arrProducts;

			} else {
				exit('wrong lottery');
			}
		}

		$this->view->draw_id = $draw_id;
		$this->view->lottery = $lottery;
		$this->view->customer_id = $customer_id;
	}

	public function transactioncostsAction(){

	}

	public function billingsAction(){
		$request = $this->getRequest();
	}

	public function index2Action()
	{
		$request = $this->getRequest();
		$start = $request->getParam("start",0);
		$end = $request->getParam("end",0);
		$customer_id = $request->getParam("customer_id",0);
		$interval = $request->getParam("interval","houtly");

		if($start==0)
		{
			$start=new Zend_Date();
		} else {
			$start=new Zend_Date($start);
		}

		if($end==0)
		{
			$end=new Zend_Date();
		} else {
			$end=new Zend_Date($end);
		}

		if($start->isEarlier($end))
		{
			exit("wrong date range");
		}

		$this->view->filter = Array(
			"start"=>$start->toString('yyyy-MM-dd'),
			"end"=>$end->toString('yyyy-MM-dd'),
			"customer_id"=>$customer_id,
			"interval"=>$interval
		);


		$start->sub(50,Zend_Date::DAY);

		// get data
		$obj_s = new Default_Model_Stats();
		$data = $obj_s->getStatsData($start->get(Zend_Date::TIMESTAMP),$end->get(Zend_Date::TIMESTAMP),0);

		print_r($data);
//		exit;

exit;
	}
	public function useronlineAction(){

		$request = $this->getRequest();
		$objStats = new Default_Model_Stats_Useronline();
		$this->view->arrCountries = getSortedCountryList();
		$page	= $request->getParam('page',1);
		//print_r($request->getParams());exit;
		$searchParams = Array('start'=>'','end'=>'','interval'=>'','country'=>'','customer_id' => '');
		$params = $request->getParams();

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

		$start = $request->getParam("start",0);
		$end = $request->getParam("end",0);
		$customer_id = $searchParams['customer_id'];

		if($start==0)
		{
			$start=new Zend_Date(null,"de_DE");
		} else {
			$start=new Zend_Date($start,"de_DE");
		}

		if($end==0)
		{
			$end=new Zend_Date(null,"de_DE");
		} else {
			$end=new Zend_Date($end,"de_DE");
		}



		$start->setHour(0);
		$start->setMinute(0);
		$start->setSecond(0);

		$end->setHour(23);
		$end->setMinute(0);
		$end->setSecond(0);

		$searchParams['start'] = $start->toString('yyyy-MM-dd');
		$searchParams['end'] = $end->toString('yyyy-MM-dd');
		$this->view->filter = $searchParams;

		$obj_s = new Default_Model_Statsuser();
		$this->view->display_type = $obj_s->getType($searchParams['start'],$searchParams['end']);
		$data = $obj_s->getStatsData($start->get(Zend_Date::TIMESTAMP),$end->get(Zend_Date::TIMESTAMP),$searchParams['country'],0);
		$this->view->data =$data->toArray();



	}

	public function livestatsajrAction(){
		$obj_s = new User_Model_Session();
		$select = $obj_s->select();
		$select->from("sessions", Array("id" => "id","role" => "role", "c" => "COUNT(role)"));
		$select->group("role");
		$arrData = $obj_s->fetchAll($select)->toArray();

		$arrRoles['total'] = 0;
		foreach($arrData as $data){
			$arrRoles[$data['role']] = $data['c'];
			$arrRoles['total'] = $arrRoles['total'] + $data['c'];
		}
		$arrEmails = Array(
			'total'		=> 5,
			'waiting'	=> 2,
			'processing'=> 3
		);

		$response = Array(
			'success'	=> true,
			'sessions'	=> $arrRoles,
			'emails'	=> $arrEmails
		);

		echo Zend_Json_Encoder::encode($response);
		exit;
	}

}