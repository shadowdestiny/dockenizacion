<?php

class Logic_Payout
{
	
	public function payout($user_id=0,$amount=0)
	{	

		$obj_u =  new user_Model_User();
		$select = $obj_u->select();
		$select->where("user_id=?",$user_id);
		$user = $obj_u->fetchRow($select);
		
		if($user)
		{

			$win = ( $user->win - $amount );			
			$obj_u->update( Array("win"=>$win),"user_id=".$user_id);
						
			info($user_id,"Payout for User: ".$user->user_id." Old Win amount: ".$user->win." New Win amount: ".$win);
			return true;
		} else {
			alert("error for payout for user ".$user_id);
			return false;
		}		
		
	}
}