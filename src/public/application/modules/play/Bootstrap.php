<?php

class Play_Bootstrap extends Zend_Application_Module_Bootstrap
{
	public function _initRoute()
	{
		$fc = Zend_Controller_Front::getInstance();
		$router = $fc->getRouter();



		// Shopping Cart / Warenkorb
		$play = new Zend_Controller_Router_Route(
			':lang/play',
			array(
				'module' =>"play",
				'controller' => 'index',
				'action'	 => 'index',
				"lang"=>Settings::get("S_lang"),
			),
			Array
			(
				1=>"lang"
			)
		);
		$router->addRoute("Play_Index",$play );

		// Shopping Cart / Warenkorb
		$play = new Zend_Controller_Router_Route(
			':lang/multipeoverlay',
			array(
				'module' =>"play",
				'controller' => 'index',
				'action'	 => 'multipletipajr',
				"lang"=>Settings::get("S_lang"),
			),
			Array
			(
				1=>"lang"
			)
		);
		
		$router->addRoute("Play_Multipletipajr",$play );
		// Shopping Cart / Warenkorb
		$play = new Zend_Controller_Router_Route(
			':lang/quickhelpoverlay',
			array(
				'module' =>"play",
				'controller' => 'index',
				'action'	 => 'quickhelpajr',
				"lang"=>Settings::get("S_lang"),
			),
			Array
			(
				1=>"lang"
			)
		);
		$router->addRoute("Play_Quickhelp",$play );		
	}
}
