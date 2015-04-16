<?

class Application_Plugin_Session extends Zend_Controller_Plugin_Abstract
{
	
	public function preDispatch(Zend_Controller_Request_Abstract $request) 
	{
		$layout = Zend_Layout::getMvcInstance();
		$view = $layout->getView();
		
		$auth = Zend_Auth::getInstance();
		if ( $auth->hasIdentity() )
		{		
			$auth = Zend_Auth::getInstance();			
			$user_id = $auth->getIdentity()->user_id;
			$role = $auth->getIdentity()->role;
			$view->isLoggedIn = true;
		} else {
			$user_id=0;
			$role = "guest";
			$view->isLoggedIn = false;
			
		}
		
		$session_id = session_id();
		
		require_once(APPLICATION_PATH."/modules/user/models/Session.php");
		$obj_s = new User_Model_Session();
		
		// clean up
		$obj_s->delete("last_action<=".(time()-600));
		
		
		$select = $obj_s->select();
		$select->where("session_id=?",$session_id);
		$data = $obj_s->fetchRow($select);
		if($data)
		{
			$arrData = Array(						
			"user_id"=>$user_id,
			"role"=>$role,
			"user_agent"=>$_SERVER['HTTP_USER_AGENT'],
			"ip"=>$_SERVER['REMOTE_ADDR'],
			"current_uri"=>$_SERVER['REQUEST_URI'],
			"host"=>$_SERVER['HTTP_HOST'],
			"last_action"=>time(),
			"count_action"=>($data->count_action+1),
			);
			
			$obj_s->update($arrData,"session_id='".$data->session_id."'");
		}
		else
		{
			$arrData = Array(
			"user_id"=>$user_id,
			"session_id"=>$session_id,
			"role"=>$role,
			"user_agent"=>$_SERVER['HTTP_USER_AGENT'],
			"ip"=>$_SERVER['REMOTE_ADDR'],
			"current_uri"=>$_SERVER['REQUEST_URI'],
			"host"=>$_SERVER['HTTP_HOST'],
			"last_action"=>time(),
			"count_action"=>1,
			);
			$obj_s->insert($arrData);
		}
	}
}
?>