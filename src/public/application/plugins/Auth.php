<?

class Application_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{
	private $request;

	public function preDispatch(Zend_Controller_Request_Abstract $request) 
	{
		// logout
		//$this->logout();
		
		$this->request=$request;
		
		$obj_u = new User_Model_User();
		
		// if not logged in
		if( !Zend_Auth::getInstance()->hasIdentity()) 
		{						
			$layout = Zend_Layout::getMvcInstance();
			$view = $layout->getView();

			if($this->request->isPost()) 
			{		
				if( 
					$this->request->getPost('act')=="login" 
					&& $this->request->getPost('username')<>"" 
					&& $this->request->getPost('password') <>"") 
				{

				
					$filter = new Zend_Filter_StripTags();
					$username = $filter->filter($this->request->getPost('username'));
					$password = $filter->filter($this->request->getPost('password'));
					
					if($this->login($username,$password,true) )
					{						
						Zend_Session::rememberMe(60*60*24*7*4);
						
						$this->request->setModuleName("user");
						$this->request->setControllerName("account");
						$this->request->setActionName("index");
					} else {
		
						$this->request->setModuleName("user");
						$this->request->setControllerName("auth");
						$this->request->setActionName("login");	
						$this->request->setParam("error",true);
					}
				}
			}
			elseif($this->request->getParam("uc","")<>"")
			{
				$user_code=$this->request->getParam("uc");
				
				$select = $obj_u->select();
				$select->where("user_code=?",$user_code);
				$data = $obj_u->fetchRow($select);
				if($data)
				{				
					$this->login($data->username,$data->password,false);
				}
				else
				{
					$this->request->setModuleName("user");
					$this->request->setControllerName("auth");
					$this->request->setActionName("login");	
					$this->request->setParam("error",true);
				}
			}
			else
			{

			}
		}		
		else
		{
			$identity = Zend_Auth::getInstance()->getIdentity();
						
			$userData = Array(
			"last_ip"=>$_SERVER['REMOTE_ADDR'],
			"last_action"=>Zend_Date::now()->toString('yyyyMMddHHmmss')
			);
			$obj_u->update($userData,"user_id=".$identity->user_id);
			
			if(
				$this->request->isPost() 
				&& $this->request->getParam("act")=="logout")
			{			
				$this->logout();
			}
		}
	}

	public function login($username,$password,$encrypted=FALSE) 
	{
	
		$logger = Zend_Registry::get("Zend_Log");
		
		$authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
		$authAdapter->setTableName('users')
			->setIdentityColumn('username')
			->setCredentialColumn('password');

		if($encrypted)
		{		
			$pw = md5($password);
		}
		else
		{
			$pw = $password;
		}

		$layout = Zend_Layout::getMvcInstance();
		$view = $layout->getView();

		$authAdapter->setIdentity($username)->setCredential($pw );
		$auth = Zend_Auth::getInstance();
		$result = $auth->authenticate($authAdapter);

		if($result->isValid()) 
		{			
			$identity = $authAdapter->getResultRowObject();

			$logger->setEventItem('user_id',$identity->user_id);
			
			if($identity->active == 1) 
			{
				$authStorage = $auth->getStorage();
				$authStorage->write($identity);
				
				$obj_u = new User_Model_User();
	
				$userData = Array(
				"last_ip"=>$_SERVER['REMOTE_ADDR'],
				"last_login"=>Zend_Date::now()->toString('yyyyMMddHHmmss'),
				"last_action"=>Zend_Date::now()->toString('yyyyMMddHHmmss')
				);
				$obj_u->update($userData,"user_id=".$identity->user_id);
				
				$logger->login("Login: ".$username);			
				return true;
			} else {
				$auth->clearIdentity();
				try {session_destroy();} catch (Exception $exc) {}
				
				$logger->login_error("User Inaktiv: ".$username);
				return FALSE;
			}
		} else {			
			$auth->clearIdentity();
			try {session_destroy();} catch (Exception $exc) {}
			$logger->login_error("Fehler beim Login: ".$username." - ".$password);
			return false;
		}
	}

	public function logout() 
	{
		$logger = Zend_Registry::get("Zend_Log");
		if( Zend_Auth::getInstance()->hasIdentity()) {
			
			$logger->setEventItem('user_id',Zend_Auth::getInstance()->getIdentity()->user_id);

			$identity = Zend_Auth::getInstance()->getIdentity();
			$obj_u = new User_Model_User();
				
			$userData = Array(
			"last_ip"=>$_SERVER['REMOTE_ADDR'],
			"last_action"=>Zend_Date::now()->toString('yyyyMMddHHmmss')
			);
			$obj_u->update($userData,"user_id=".$identity->user_id);
				
				
			try {session_destroy();} catch (Exception $exc) {}
			Zend_Auth::getInstance()->clearIdentity();
			$logger->logout("Logout: ".$username);
			//Header("Location: ".$_SERVER["REQUEST_URI"]);
			Header("Location:  /");
			exit();
		}
	}
}
?>