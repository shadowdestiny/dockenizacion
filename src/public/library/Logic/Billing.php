<?php

class Logic_Billing
{
	public $um;
	public $userData = Array();
	
	public function Logic_Billing($user_id=0)
	{	
		$this->um = new User_Manager();
		
		if($user_id>0)
		{			
			$this->userData = $this->um->getUserData($user_id);
		}
	}
	
	public function getCurrentBudget($user_id)
	{
		if($user_id>0)
		{
			return 123;
		} else {
			return false;
		}
	}
	
	
	
	public function payIn($user_id=0,$amount=0,$biller="",$biller_transaction_id="")
	{
		if($user_id>0)
		{
			$obj_u = new User_Model_User();
			$select = $obj_u->select();
			$select->where("user_id=?",$user_id);
			$user = $obj_u->fetchRow($select);
			if($user)
			{
				$obj_t = new Billing_Model_Transactions();
				
				
				$user->budget = ($user->budget + $amount);
				
				$obj_u->update($user->toArray(),"user_id=".$user_id);
				$this->um->resetUserData($user_id);

				$transaction_id = $obj_t->addTransaction("payin",$user_id,$amount,$biller,$biller_transaction_id,$order_id);
				return $transaction_id;
				
			} else {
				alert("Billing: addBudget: User not found: ".$user_id.", Budget: ".$budget.", Transaction: ".$transaction_id);
				return false;
			}
		}
	}
	
	
	
	public function addBudget($user_id=0,$budget=0,$biller="",$biller_transaction_id="")
	{
		if($user_id>0)
		{
			$obj_u = new User_Model_User();
			$select = $obj_u->select();
			$select->where("user_id=?",$user_id);
			$user = $obj_u->fetchRow($select);
			if($user)
			{
				$obj_t = new Billing_Model_Transactions();
				
				
				$user->budget = ($user->budget + $budget);
				$user->budget;
				
				$obj_u->update($user->toArray(),"user_id=".$user_id);
				$this->um->resetUserData($user_id);

				$transaction_id = $obj_t->addTransaction("payin",$user_id,$budget,$biller,$biller_transaction_id,$order_id);
				return $transaction_id;
				
			} else {
				alert("Billing: addBudget: User not found: ".$user_id.", Budget: ".$budget.", Transaction: ".$transaction_id);
				return false;
			}
		}
	}
	
	
	public function payOrder($user_id=0,$order_id=0,$biller="",$biller_transaction_id="")
	{
		if($user_id>0)
		{
			$obj_u = new User_Model_User();
			$select = $obj_u->select();
			$select->where("user_id=?",$user_id);
			$user = $obj_u->fetchRow($select);
			if($user)
			{
				$obj_t = new Billing_Model_Transactions();
				
				
				$user->budget = ($user->budget + $budget);
				
				$obj_u->update($user->toArray(),"user_id=".$user_id);
				$this->um->resetUserData($user_id);

				$transaction_id = $obj_t->addTransaction("payin",$user_id,$budget,$biller,$biller_transaction_id,$order_id);
				return $transaction_id;
				
			} else {
				alert("Billing: addBudget: User not found: ".$user_id.", Budget: ".$budget.", Transaction: ".$transaction_id);
				return false;
			}
		}
	}
}