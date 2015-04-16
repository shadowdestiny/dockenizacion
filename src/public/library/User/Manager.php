<?

class User_Manager extends User_Validator
{
	public $user_id=0;
			
	public function User_Manager($user_id=NULL)
	{
		if($user_id<>NULL)
		{
			$this->user_id = $user_id;
		}
	}

	public function getUserData($user_id=NULL)
	{
		if($user_id<>NULL)
		{
			if($user_id>0)
			{
				// ask for this user id
			} else {
				$user_id = $this->user_id;
			}
		} else {
			$user_id=$this->user_id;
		}
		
		$cache = Zend_Registry::get("Zend_Cache");
		
		$obj_u = new User_Model_User();
		$obj_ud = new User_Model_User_Details();
		
		$cache_key = "user_data_".$user_id;
		
		if($cache->test($cache_key))
		{
			return $cache->load($cache_key);
		} else {
			$data = $obj_u->fetchRow("user_id=".$user_id);
			if($data)			
			{			
				$details = $obj_ud->fetchRow("user_id=".$user_id);

				if($details)
				{
					$arrUserData = $data->toArray();
					$arrUserData = array_merge($arrUserData, $details->toArray());
					
					$cache->save($arrUserData,$cache_key,Array("user","user_data_".$user_id),3600);					
					return $arrUserData;
				}				
			}
		}
		return false;
	}
	
	
	
	public function generatePassword ( $min = 6, $max = 12, $numeric = TRUE, $specialChars = false )
	{
		$chars = $this->chars;
		if($numeric) $chars .= $this->numeric;

		if($specialChars) $chars .= $this->specialChars;

		if(!($min>0 && $max>0 && $min<=$max)) {
			$min = 6;
			$max = 12;
		}

		$arr_chars = explode(" ", $chars);
		shuffle($arr_chars);
		$num = rand($min, $max);
		$tmp = "";
		for($i=0; $i<$num; $i++) {
			$tmp .= $arr_chars[$i];
		}
		return $tmp;
	}
	
	public function resetUserData($user_id = 0 )
	{
		if($user_id  == 0 )
		{
			$user_id = $this->user_id;
		}
		
		$cache = Zend_Registry::get("Zend_Cache");		
		$cache_key = "user_data_".$user_id;
		$cache->remove($cache_key);
		$this->getuserData($user_id);		
	}
	
	function hasCompleteProfile( $user_id = 0)
	{
		if($user_id == 0)
		{
			$user_id = $this->user_id;
		}
		
		$userData = $this->getUserData($user_id);
		if($userData)
		{
			if(
				$userData['first_name']<>""
				&& $userData['last_name']<>""
				&& $userData['zip']<>""
				&& $userData['city']<>""
				&& $userData['street']<>""
				&& $userData['terms']<>0
			)
			{
				return true;
			} else {
				return false;
			}			
		} else {
			return false;
		}
		
	}
}


?>