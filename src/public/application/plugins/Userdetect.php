<?

class Application_Plugin_Userdetect extends Zend_Controller_Plugin_Abstract
{
	public function preDispatch(Zend_Controller_Request_Abstract $request)
    {		
		if( Zend_Auth::getInstance()->hasIdentity())
		{		
			$identity = Zend_Auth::getInstance()->getIdentity();
			
			$um = new User_Manager($identity->user_id);
			$userData = $um->getUserData($identity->user_id);
			Zend_Registry::set("user_data",$userData);
		}
		else
		{
			Zend_Registry::set("user_data",Array());
			$userData = Array();
		}
		
		$layout = Zend_Layout::getMvcInstance();
		$view = $layout->getView();
		$view->userData = $userData;
		
    }
}