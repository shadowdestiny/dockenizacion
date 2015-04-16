<?


class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	public $frontController;

	protected function _initAutoload()
	{

		require_once(APPLICATION_PATH . "/../library/functions.php");
		require_once(APPLICATION_PATH . "/../library/Tpl/Settings.php");
		require_once(APPLICATION_PATH . "/../library/Tpl/Tracking.php");

		Zend_Layout::startMvc();

		$this->bootstrap('frontController');
		$fc = Zend_Controller_Front::getInstance();
		
		$this->bootstrap('Log');
		$this->bootstrap('cli');

/*
		$modelLoader = new Zend_Application_Module_Autoloader(array(
			'namespace' => '',
			'basePath' => APPLICATION_PATH.'/modules/'));
*/
		if(!defined("isCron"))
		{
		
			//$fc->registerPlugin(new Application_Plugin_Performance());

			$fc->registerPlugin(new Application_Plugin_Auth());
			$fc->registerPlugin(new Application_Plugin_Acl());

			$fc->registerPlugin(new Application_Plugin_Userdetect());

			$fc->registerPlugin(new Application_Plugin_Session());
			$fc->registerPlugin(new Application_Plugin_Language());
			$fc->registerPlugin(new Application_Plugin_Layout());
			$fc->registerPlugin(new Application_Plugin_Lottery());


		}

	}

	public function asd()
	{
	exit("X");
		try
		{
			$config = new Zend_Config(Array(),True);
			$config->merge(new Zend_Config_Ini(APPLICATION_PATH."/configs/application.ini",APPLICATION_ENV));

			$db = Zend_Db::factory('Pdo_Mysql', array(
				'host'	 =>$config->resources->db->params->host,
				'username' =>$config->resources->db->params->username,
				'password' => $config->resources->db->params->password,
				'dbname'   => $config->resources->db->params->dbname,
				'charset'  => $config->resources->db->params->charset,
				'profiler' => array(
					'enabled' => $config->resources->db->params->profiler->enabled,
					//'class'   => 'Application_Plugin_Initialize'
			)
			));

			$db->getConnection();

			/* Filter für Profiler setzen */

			$profiler=$db->getProfiler();
			$profiler->setFilterElapsedSecs(0);
			/*
			 $profiler->setFilterQueryType(Zend_Db_Profiler::SELECT |
			Zend_Db_Profiler::INSERT |
			Zend_Db_Profiler::DELETE |
			Zend_Db_Profiler::UPDATE);
			*/

			Zend_Db_Table_Abstract::setDefaultAdapter($db);
			Zend_Db_Table::setDefaultAdapter($db);

			Zend_Registry::set("db",$db);
		}
		catch(EXCEPTION $e)
		{
			$this->setError("db",$e);
		}
	}

	protected function _initLog()
	{

		$resource = $this->getPluginResource('db');
		$dbAdapter = $resource->getDbAdapter();


		$columnMapping = array(
        'level' => 'priorityName',
        'priority' => 'priority',
        'message' => 'message',
        'created' => 'timestamp',
        'user_agent'=> 'user_agent',
		'uri'=> 'uri',
		'referer'=> 'referer',
        'get_vars' => 'get_vars',
        'post_vars' => 'post_vars',
        'ip' => 'ip',
        'user_id' => 'user_id',
		);

		$auth = Zend_Auth::getInstance();

		$writerDb = new Zend_Log_Writer_Db($dbAdapter, 'log_system', $columnMapping);
		$logger = new Zend_Log($writerDb);

		$uri = (isset($_SERVER['REQUEST_URI'])? $_SERVER['REQUEST_URI']:"");
		$referer = (isset($_SERVER['HTTP_REFERER'])? $_SERVER['HTTP_REFERER']:"");
		$user_agent = (isset($_SERVER['HTTP_USER_AGENT'])? $_SERVER['HTTP_USER_AGENT']:"");
		$remote_address = (isset($_SERVER['REMOTE_ADDR'])? $_SERVER['REMOTE_ADDR']:"");

		$logger->setEventItem('datetime',date('Y-m-d H:i:s'));
	    $logger->setEventItem('user_agent',$user_agent);
		$logger->setEventItem('uri',$uri);
		$logger->setEventItem('referer',$referer);
	    $logger->setEventItem('get_vars',print_r($_GET,true));
	    $logger->setEventItem('post_vars',print_r($_POST,true));
	    $logger->setEventItem('ip',$remote_address);
	    if($auth->hasIdentity())
		{
			$logger->setEventItem('user_id',$auth->getIdentity()->user_id);
			//$logger->setEventItem('user_id',0);
		} else {
			$logger->setEventItem('user_id',0);
		}


		$logger->addPriority('login', 10);
		$logger->addPriority('logout', 11);
		$logger->addPriority('transfailed', 12);
		$logger->addPriority('login_error', 13);

		//$logger->login("login","lgin");
		//$logger->info("TEST");

		$this->log = $logger;

		Zend_Registry::set('Zend_Log', $logger);
	}

	public function _initCache()
	{
		$noCache=false;
		try
		{
			$oBackend = new Zend_Cache_Backend_Memcached(
					array(
						'servers' => array( array( 'host' => 'localhost', 'port' => '11211')),
						'compression' => false
					)
				);

				$oFrontend = new Zend_Cache_Core(
					array(
						'lifetime' => 60,
						'caching' => true,
						'cache_id_prefix' => APPLICATION_ENV,
						'write_control' => true,
						'automatic_serialization' => true,
						'ignore_user_abort' => true
					)
				);

			$oCache = Zend_Cache::factory( $oFrontend, $oBackend );
			try
			{
				$oCache->save("CACHETEST","CACHETEST",Array(),1);
				if(!$oCache->test("CACHETEST"))
				{
					$noCache=True;
				}
				else
				{
					// Cache für Meta-Daten
					Zend_Db_Table_Abstract::setDefaultMetadataCache($oCache);
				}
			}
			catch(EXCEPTION $e)
			{
				throw new exception($e);
			}
		}
		catch(Exception $e)
		{
			$noCache=TRUE;
		}



		if($noCache)
		{
			try
			{
				$oBackend = new Zend_Cache_Backend_BlackHole(
					array(
					)
				);

				$oFrontend = new Zend_Cache_Core(
				   	array(
						'caching' => true,
						'write_control' => true,
						'automatic_serialization' => true,
						'ignore_user_abort' => true
				) );

				$oCache = Zend_Cache::factory( $oFrontend, $oBackend);
			}
			catch(EXCEPTION $e)
			{
				$this->setError("cache","Cache Error");
			}
		}

		Zend_Registry::set("Zend_Cache",$oCache);
//		$oCache->clean();
	}

	protected function _initCli()
    {

		if(defined("isCron") && isCron)
		{
	        if (strrpos(strtolower(PHP_SAPI), 'cli') !== false) {
	            $this->bootstrap('frontController');

				$this->frontController->unregisterPlugin("Language");
				$this->frontController->unregisterPlugin("Layout");
				$this->frontController->unregisterPlugin("Auth");
				$this->frontController->unregisterPlugin("Acl");
				$this->frontController->unregisterPlugin("Session");


				$this->frontController = Zend_Controller_Front::getInstance();
				$this->frontController = $this->getResource('FrontController');
	            $this->frontController
	                ->setRouter(new Tpl_Cli_RouterCli())
	                ->setRequest(new Tpl_Cli_RequestCli())
	                ->setResponse(new Tpl_Cli_ResponseCli());;
	        }
		}
    }

	public function _initRoute()
	{
		if(defined("isCron") && isCron)
		{
			return true;
		}
		$this->bootstrap('FrontController');

		$fc = Zend_Controller_Front::getInstance();
		$router = $fc->getRouter();


        $langRoute = new Zend_Controller_Router_Route(
            ':lang/',
            array(
                'lang' => 'en',
            )
        );

        $defaultRoute = new Zend_Controller_Router_Route(
            ':module/:controller/:action/*',
            array(
                'module'=>'default',
                'controller'=>'index',
                'action'=>'index'
            )
        );
		$router->addRoute('default', $defaultRoute);

        $defaultRoute = $langRoute->chain($defaultRoute);

        $router->addRoute('langRoute', $langRoute);
        $router->addRoute('defaultRoute', $defaultRoute);
		$router->setGlobalParam('lang', "en");

	}

	protected function _initTranslate()
    {

		$this->bootstrap("db");
		$this->bootstrap("cache");

		$registry = Zend_Registry::getInstance();
		$cache = $registry->get("Zend_Cache");

        $translate = new Zend_Translate("Tpl_Translator",
                    Array(
						"home"=>"Startseite",
						"programs"=>"Programme",
						"categories"=>"Kategorien"
						),
						"de"
					);

		

		$registry->set('Zend_Translate', $translate);
		$translate->setLocale('en');
    }

}
