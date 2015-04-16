<?

class Tpl_Cli_RequestCli extends Zend_Controller_Request_Abstract
{


	public function __construct()
	{
		$opts = new Zend_Console_Getopt(
		array(
                'module|m-s'     => 'module name',
                'controller|c-s' => 'controller name',
                'action|a-s'     => 'action name',
				'id|i-s'     => 'id'
		)
		);

		$params = array_merge($opts->getOptions(), $opts->getRemainingArgs());
		foreach ($params as $param) {
			$this->setParam($param, $opts->getOption($param));
		}

		return $this;
	}
	
	public function isPost()
	{
	}
	
	public function getRequestUri()
	{
	}
	
	public function getBaseUrl()
	{
	}
	
} 
?>