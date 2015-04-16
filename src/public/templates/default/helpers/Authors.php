<?

class Zend_View_Helper_Authors
{
	public $view;

	public function Authors()
	{
		return $this;
	}

	public function getName($user_id = NULL)
	{
		if ($user_id){

			$cache = Zend_Registry::get("Zend_Cache");
			$cache_key = "user_data_".$user_id;

			if($cache->test($cache_key))
			{
				$arrUserData = $cache->load($cache_key);
			} else {
				$obj_u = new User_Model_User();
				$userData = $obj_u->find($user_id);
				if($userData)
				{
					$arrUserData = $userData[0]->toArray();

					$cache->save($arrUserData,$cache_key,Array("user_".$user_id),86400);

				}
				else
				{
					return '';
				}
			}
		} else {
			return '';
		}

		return $arrUserData['first_name']." ".$arrUserData['last_name'];
	}


	public function setView(Zend_View_Interface $view)
	{
		$this->view = $view;
	}
}