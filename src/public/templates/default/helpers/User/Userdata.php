<?

class Zend_View_Helper_User_Userdata
{
	public $view;
	private $user_data;

	public function User_Userdata($user_id = NULL)
	{
		if($user_id != NULL){
			$um = new User_Manager();
			$this->user_data = $um->getUserData($user_id);
		} else {
			$this->user_data = Zend_Registry::get('user_data');
		}
		return $this;
	}

	public function getUserData()
	{
		return $this->user_data;
	}

	public function getBalance($type = NULL){
		if ($type == 'budget'){
			$amount = $this->user_data['budget'];
		} elseif ($type == 'win'){
			$amount = $this->user_data['win'];
		} else {
			$amount = $this->user_data['budget'] + $this->user_data['win'];
		}
		return $amount;
	}


	public function setView(Zend_View_Interface $view)
	{
		$this->view = $view;
	}
}