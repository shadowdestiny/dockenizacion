<?php

class Lottery_Bootstrap extends Zend_Application_Module_Bootstrap
{
	public function _initRoute()
	{
		$router = Zend_Controller_Front::getInstance()->getRouter();

		$route = new Zend_Controller_Router_Route(
		':lang/@seo_lottery_url_keyword/:page',
		    array(
				'module' =>'lottery',
				'controller' => 'index',
		        'action'     => 'index',
				'page'=>1,
				'lang' => "en",
				"type"=>"index",
		    ),
			array(
				1=>"lang",
				2=>"page",
			)
		);
		$route->assemble();
		$router->addRoute('Lottery_Index', $route);


		$route = new Zend_Controller_Router_Route(
		':lang/@seo_lottery_url_keyword/archive/:date',
		    array(
				'module' =>'lottery',
				'controller' => 'index',
		        'action'     => 'index',
				'date'=>"",
				'lang' => "en",
				"type"=>"archive",
		    ),
			array(
				1=>"lang",
				2=>"date",
			)
		);
		$route->assemble();
		$router->addRoute('Lottery_Archive', $route);

		$route = new Zend_Controller_Router_Route(
		':lang/@seo_lottery_url_keyword/@seo_winners/:date/:draw_id',
		    array(
				'module' =>'lottery',
				'controller' => 'index',
		        'action'     => 'show',
				'date'=>0,
				'draw_id'=>0,
				'lang' => "en",
		    ),
			array(
				1=>"lang",
				2=>"date",
				3=>"draw_id",
			)
		);
		$route->assemble();
		$router->addRoute('Lottery_Show', $route);
	}
}
