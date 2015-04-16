<?php

class Contact_Bootstrap extends Zend_Application_Module_Bootstrap
{
	public function _initRoute()
	{
		$fc = Zend_Controller_Front::getInstance();
		$router = $fc->getRouter();



		// Shopping Cart / Warenkorb
		$play = new Zend_Controller_Router_Route(
			':lang/contact',
			array(
				'module' =>"contact",
				'controller' => 'index',
				'action'	 => 'index',
				"lang"=>Settings::get("S_lang"),
			),
			Array
			(
				1=>"lang"
			)
		);
		$router->addRoute("Contact_Index_Index",$play );
	}
}
