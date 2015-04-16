<?

class Tpl_Cli_RouterCli extends Zend_Controller_Router_Abstract
{
	public function assemble($userParams, $name = null, $reset = false, $encode = true)
	{
		return '';
	}

	public function route(Zend_Controller_Request_Abstract $dispatcher)
	{
		$frontController = $this->getFrontController();

		$module     = $dispatcher->getParam('module', $frontController->getDefaultModule());
		$controller = $dispatcher->getParam('controller', $frontController->getDefaultControllerName());
		$action     = $dispatcher->getParam('action', $frontController->getDefaultAction());

		$dispatcher->setModuleName($module);
		$dispatcher->setControllerName($controller);
		$dispatcher->setActionName($action);

		return $this;
	}

	public function addRoute()
	{
	}
	
	public function setGlobalParam()
	{
	
	}
	
	
}