<?php

class User_Account_TicketController extends Zend_Controller_Action
{
	public $um;
	public $userData = Array();


    public function init()
    {
        $this->um = new User_Manager();
		$this->userData = Zend_Registry::get("user_data");
		$this->view->userData = $this->userData;
		if(!Zend_Auth::getInstance()->hasIdentity())
		{
			$this->redirect($this->view->url(Array(),"User_Login"));
		}
    }

    public function indexAction(){
		$request = $this->getRequest();
		$this->view->popup = $request->getParam('popup', false);


		if($this->view->popup){
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
			$html = $this->view->render("user/account/ticket/index.phtml");

			$response=Array("success"=>true,"html"=>$html);
			echo json_encode($response);
		}
	}

	public function detailAction()
	{
		$request = $this->getRequest();
		$post_id = 000;


		$this->view->current_draw_id = 700;

		$arrPostDetails = Array(
			"total_price"	=> 55.55,
			"draw_id"		=> 700,
			"start_draw_date" => '2014-12-02',
			"win"=>	0.12,
			"num_of_draws"		=> 4,
			"items"=> Array(
				0	=> Array(
					"numbers"			=> Array(5,9,22,23,28),
					"stars"				=> Array(3,7),
					"draws_id"			=> 700,

					"price"				=> 8.22,
					"start_draw_date"	=> "2014-11-11",
					"id"				=> 11223,
					"ticket_type_id"	=> 1,
					"ticket_type"		=> 'single',
				),
				1	=> Array(
					"numbers"			=> Array(5,9,22,23,5,7,28),
					"stars"				=> Array(3,7,11),
					"draws_id"			=> 700,
					"price"				=> 8.22,
					"start_draw_date"	=> "2014-11-11",
					"id"				=> 11223,
					"ticket_type_id"	=> 1,
					"ticket_type"		=> 'multi',
				),
				2	=> Array(
					"numbers"			=> Array(2,1,3,8,23,33,51),
					"stars"				=> Array(2,5),
					"draws_id"			=> 700,
					"price"				=> 8.22,
					"start_draw_date"	=> "2014-11-11",
					"id"				=> 11223,
					"ticket_type_id"	=> 1,
					"ticket_type"		=> 'multi',
				)
			)
		);
		$this->view->details = $arrPostDetails;
		$obj_draws = new Lottery_Euromillions_Draw;
		$tmpdate = new Zend_Date($arrPostDetails['start_draw_date']);
		$draw_id = $obj_draws->getDrawIdForDrawDate($tmpdate);

		for ($i = $draw_id; $i < ($draw_id + $arrPostDetails['num_of_draws']); $i++) {
			$draws[] = Array(
				"draw_id"	=> $i,
				"draw_date" => $obj_draws->getDrawDateForDrawId($i),
				"status"	=> 'open'
				);

		}
		$this->view->draws = $draws;

	}
}