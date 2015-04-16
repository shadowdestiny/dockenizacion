<?php

class News_Bootstrap extends Zend_Application_Module_Bootstrap
{
	public function _initRoute()
	{
		$router = Zend_Controller_Front::getInstance()->getRouter();


		$route = new Zend_Controller_Router_Route(
		':lang/@seo_news_url_keyword/:alias',
		    array(
				'module' =>'news',
				'controller' => 'index',
		        'action'     => 'show',
				'alias'=>"index",
				'lang' => "en",
		    ),
			array(
				1=>"lang",
				2=>"alias",

			)
		);
		$route->assemble();
		$router->addRoute('News_Show', $route);

		$route = new Zend_Controller_Router_Route(
		':lang/@seo_news_url_keyword/page/:page',
		    array(
				'module' =>'news',
				'controller' => 'index',
		        'action'     => 'index',
				'page' => 1,
				'lang' => "en"
		    ),
			array(
				1=>"lang",
				2=>"page",
			)
		);
		$route->assemble();
		$router->addRoute('News_Index', $route);

	}
}