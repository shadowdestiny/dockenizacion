<?php

class Lottery_Cron_JackpotController extends Zend_Controller_Action
{
	private $obj_m;
	    		
	
	/*
		send email to all users which has set a jackpot reminder
	*/
	
	public function init()
	{
		$this->obj_m = new Mail_Manager();
	}
	
	public function sendjackpotreminderAction()
	{
	
		info("Cron: Start: JackpotReminder");
		
		$success=0;
		$error=0;
		$countTotal=0;
				
		$obj_ld = new Lottery_Model_Euromillions_Draws();
		$jackpot = $obj_ld->getNextJackpot();
						
		$amountList = Array("15000000","30000000","50000000","75000000","100000000");
		
		$request = $this->getRequest();
		
		$obj_ud = new User_Model_User_Details();
		
		$select = $obj_ud->select();
		$select->where("user_id in (select user_id from users where active=1)");
		$select->where("jackpot>0");
		$select->where("jackpot<=".$jackpot);
		$data = $obj_ud->fetchAll($select);
		
		$countTotal = $data->count();
		echo $countTotal;
		//exit;
		
		if( $data->count()>0)
		{
			foreach($data as $item)
			{
				if($jackpot >= $item->jackpot)
				{				
					if( $this->sendJackpotReminder($item->user_id,$jackpot) )
					{
						$success++;
					} else {
						$error++;
					}
				}
			}
		}
		info("Cron: Done: JackpotReminder Total: ".$countTotal.", Success: ".$success.", Error: ".$error);
		exit();		
	}
	
	public function sendmailAction()
	{
		
		exit("A");
	}
	
	public function sendJackpotReminder($user_id=0,$jackpot=0)
	{
		if($user_id>0 && $jackpot>0)
		{
			$this->obj_m->setUser($user_id);
			$params=Array(
			"current_jackpot"=>$jackpot
			);
			return $this->obj_m->sendJackpotReminder($params);
		}
	}
}