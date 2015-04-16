<?php

class Faq_Bootstrap extends Zend_Application_Module_Bootstrap
{
	public function _initRoute()
	{
		$router = Zend_Controller_Front::getInstance()->getRouter();		

		$route = new Zend_Controller_Router_Route(
		':lang/faq',
		    array(
				'module' =>'faq',
				'controller' => 'index',
		        'action'     => 'index',
		    )
		);
		$router->addRoute('Faq_Index', $route);						

	}				
}
