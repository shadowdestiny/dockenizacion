<?php

class Lottery_LcController extends Zend_Controller_Action
{
	public function notificationAction()
	{
		exit("ptize");
	}
	
	public function prizeAction()
	{
		exit("prize");
	}
	
	public function testAction()
	{
		$obj_lc =new Lottery_Model_LC_Webservice();
		
		
		$arrData = Array(
		"numbers"=>Array(1,2,3,4,5),
		"stars"=>Array(1,2),
		"date"=>"2014-05-09",
		"user_id"=>1,
		"customer_id"=>1,
		"order_id"=>1,
		"em_ticket_id"=>1
		);
		
		echo $obj_lc->validateTicket($arrData);
		exit("A");
		$c= $this->encryptString("ASD","1234567890");
		echo $c."<br>";
		$e=$this->decryptString($c,"1234567890");
		echo $e;
		exit("D")    ;	
	}
}