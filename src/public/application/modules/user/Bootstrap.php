<?php

class User_Bootstrap extends Zend_Application_Module_Bootstrap
{
	public function _initRoute()
	{

		$router = Zend_Controller_Front::getInstance()->getRouter();

		$route = new Zend_Controller_Router_Route(
		':lang/login',
		    array(
				'module' =>'user',
				'controller' => 'auth',
		        'action'     => 'login',
		    )
		);
		$router->addRoute('User_Login', $route);

		$route = new Zend_Controller_Router_Route(
		':lang/change-address',
		    array(
				'module' =>'user',
				'controller' => 'account',
		        'action'     => 'completeprofile',
		    )
		);
		$router->addRoute('User_Change_Address', $route);

//$router = Zend_Controller_Front::getInstance()->getRouter();

	$route = new Zend_Controller_Router_Route(
		':lang/myaccount',
		    array(
				'module' =>'user',
				'controller' => 'account',
		        'action'     => 'index',
				'lang' => "en",
		    ),
			array(
				1=>"lang",
			)
		);
		$route->assemble();
		$router->addRoute('User_Account_Index', $route);

		$route = new Zend_Controller_Router_Route(
		':lang/mytickets/:type',
		    array(
				'module' =>'user',
				'controller' => 'account_ticket',
		        'action'     => 'index',
				'lang' => "en",
				'type'=>"active"
		    ),
			array(
				1=>"lang",
				2=>"type"
			)
		);
		$route->assemble();
		$router->addRoute('User_Account_Ticket_Index', $route);

		$route = new Zend_Controller_Router_Route(
		':lang/myticket/:group_id',
		    array(
				'module' =>'user',
				'controller' => 'account_ticket',
		        'action'     => 'detail',
				'lang' => "en",
				'group_id'=>""
		    ),
			array(
				1=>"lang",
				2=>"group_id"
			)
		);
		$route->assemble();
		$router->addRoute('User_Account_Ticket_Details', $route);

		$route = new Zend_Controller_Router_Route(
		':lang/profilesettings',
		    array(
				'module' =>'user',
				'controller' => 'account',
		        'action'     => 'profilesettings',
				'lang' => "en",
		    ),
			array(
				1=>"lang",
			)
		);
		$route->assemble();
		$router->addRoute('User_Account_Profilesettings', $route);

		$route = new Zend_Controller_Router_Route(
		':lang/settings',
		    array(
				'module' =>'user',
				'controller' => 'account',
		        'action'     => 'settings',
				'lang' => "en",
		    ),
			array(
				1=>"lang",
			)
		);
		$route->assemble();
		$router->addRoute('User_Account_Settings', $route);


		$route = new Zend_Controller_Router_Route(
		':lang/banking',
		    array(
				'module' =>'user',
				'controller' => 'account',
		        'action'     => 'banking',
				'lang' => "en",
		    ),
			array(
				1=>"lang",
			)
		);
		$route->assemble();
		$router->addRoute('User_Account_Banking', $route);

	$route = new Zend_Controller_Router_Route(
		':lang/verify',
		    array(
				'module' =>'user',
				'controller' => 'auth',
		        'action'     => 'verify',
				'lang' => "en"
				),
			array(
				1=>"lang",
			)
		);
		$route->assemble();
		$router->addRoute('User_VerifyMail', $route);
/**
	$route = new Zend_Controller_Router_Route(
		':lang/verify',
		    array(
				'module' =>'user',
				'controller' => 'auth',
		        'action'     => 'verify',
				'lang' => "en"
				),
			array(
				1=>"lang",
			)
		);
		$route->assemble();
		$router->addRoute('User_VerifyMail', $route);**/

	$route = new Zend_Controller_Router_Route(
		':lang/changemail',
		    array(
				'module' =>'user',
				'controller' => 'auth',
		        'action'     => 'changemail',
				'lang' => "en",
		    ),
			array(
				1=>"lang",
			)
		);
		$route->assemble();
		$router->addRoute('User_ChangeMail', $route);

		$route = new Zend_Controller_Router_Route(
		':lang/rp/:user_code',
		    array(
				'module' =>'user',
				'controller' => 'auth',
		        'action'     => 'resetpassword',
				'lang' => "en",
				'user_code'=>""
		    ),
			array(
				1=>"lang",
				2=>"user_code",
				3=>"is_mail"
			)
		);
		$route->assemble();
		$router->addRoute('User_ResetPw', $route);

		$route = new Zend_Controller_Router_Route(
		':lang/changepassword',
		    array(
				'module' =>'user',
				'controller' => 'account',
		        'action'     => 'changepassword',
				'lang' => "en",
				'user_code'=>""
		    ),
			array(
				1=>"lang"
			)
		);
		$route->assemble();
		$router->addRoute('User_Changepassword', $route);



		$route = new Zend_Controller_Router_Route(
		':lang/activate/:user_code',
		    array(
				'module' =>'user',
				'controller' => 'auth',
		        'action'     => 'activate',
				'lang' => "en",
				'user_code'=>"",
		    ),
			array(
				1=>"lang",
				2=>"user_code"
			)
		);
		$route->assemble();
		$router->addRoute('User_Activation', $route);


		$route = new Zend_Controller_Router_Route(
		':lang/register',
		    array(
				'module' =>'user',
				'controller' => 'auth',
		        'action'     => 'registration',
				'lang' => "en",
		    ),
			array(
				1=>"lang",
			)
		);
		$route->assemble();
		$router->addRoute('User_Registration', $route);


		$route = new Zend_Controller_Router_Route(
		':lang/forgotpassword',
		    array(
				'module' =>'user',
				'controller' => 'auth',
		        'action'     => 'forgotpassword',
				'lang' => "en",
		    ),
			array(
				1=>"lang",
			)
		);
		$route->assemble();
		$router->addRoute('User_ForgotPassword', $route);


		$route = new Zend_Controller_Router_Route_Regex(
			'uc=(.*)',
			array(
				'module' 	=> 'user',
				'controller'=> 'auth',
				'action'	=> 'login',
				'lang'		=> "en",
				'user_code'=>""
			),
			Array
			(
				1	=>	"user_code"
			),
			'?uc=%s'
		);
		$router->addRoute('User_Autologin', $route);
		
		$route = new Zend_Controller_Router_Route(
		':lang/@seo_user_auth_registr',
		    array(
				'module' =>'user',
				'controller' => 'auth',
		        'action'     => 'registration',
				'lang' => "en",
				"type"=>"index",
		    ),
			array(
				1=>"lang",
			)
		);
		$route->assemble();
		$router->addRoute('User_Auth_Registration', $route);

		



	}
}
