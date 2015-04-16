<?php

class Default_Bootstrap extends Zend_Application_Module_Bootstrap
{
	public function _initRoute()
	{
		$router = Zend_Controller_Front::getInstance()->getRouter();

		
		$route = new Zend_Controller_Router_Route(
		'ajr',
		    array(
				'module' =>'default',
				'controller' => 'ajr',
		        'action'     => 'post',
		    )
		);
		$router->addRoute('ajr', $route);

		$route = new Zend_Controller_Router_Route(
		':lang/ajr',
		    array(
				'module' =>'default',
				'controller' => 'ajr',
		        'action'     => 'post',
		    )
		);
		$router->addRoute('AJR', $route);

		$route = new Zend_Controller_Router_Route(
		'403',
		    array(
				'module' =>'default',
				'controller' => 'error',
		        'action'     => 'noaccess',
		    )
		);
		$router->addRoute('NoAccess', $route);
	}
}
