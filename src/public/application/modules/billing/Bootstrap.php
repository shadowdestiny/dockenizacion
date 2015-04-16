<?php

class Billing_Bootstrap extends Zend_Application_Module_Bootstrap
{
	public function _initRoute()
	{
		$fc = Zend_Controller_Front::getInstance();
		$router = $fc->getRouter();



		// Shopping Cart / Warenkorb
		$cart = new Zend_Controller_Router_Route(
			':lang/cart',
			array(
				'module' =>"billing",
				'controller' => 'cart',
				'action'	 => 'index',
				"lang"=>Settings::get("S_lang"),
			),
			Array
			(
				1=>"lang"
			)
		);
		$router->addRoute("Billing_Cart",$cart );

		$cart = new Zend_Controller_Router_Route(
			':lang/pay',
			array(
				'module' =>"billing",
				'controller' => 'pay',
				'action'	 => 'index',
				"lang"=>Settings::get("S_lang"),
			),
			Array
			(
				1=>"lang"
			)
		);
		$router->addRoute("Billing_Pay",$cart );

		$cart = new Zend_Controller_Router_Route(
			':lang/buy',
			array(
				'module' =>"billing",
				'controller' => 'pay',
				'action'	 => 'buy',
				"lang"=>Settings::get("S_lang"),
			),
			Array
			(
				1=>"lang"
			)
		);
		$router->addRoute("Billing_Buy",$cart );

		$payout = new Zend_Controller_Router_Route(
			':lang/payout',
			array(
				'module' =>"billing",
				'controller' => 'payout',
				'action'	 => 'index',
				"lang"=>Settings::get("S_lang"),
			),
			Array
			(
				1=>"lang"
			)
		);
		$router->addRoute("Billing_Payout",$payout );

		$payoutaccount = new Zend_Controller_Router_Route(
			':lang/payoutaccount',
			array(
				'module' =>"billing",
				'controller' => 'payout',
				'action'	 => 'account',
				"lang"=>Settings::get("S_lang"),
			),
			Array
			(
				1=>"lang"
			)
		);
		$router->addRoute("Billing_Payout_Account",$payoutaccount );

		$payoutaccount = new Zend_Controller_Router_Route(
			':lang/ibancountry',
			array(
				'module' =>"billing",
				'controller' => 'payout',
				'action'	 => 'ibancountry',
				"lang"=>Settings::get("S_lang"),
			),
			Array
			(
				1=>"lang"
			)
		);
		$router->addRoute("Billing_Payout_Ibancountry",$payoutaccount );
	}
}
