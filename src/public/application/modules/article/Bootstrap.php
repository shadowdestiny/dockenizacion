<?php

class Article_Bootstrap extends Zend_Application_Module_Bootstrap
{
	public function _initRoute()
	{

		$router = Zend_Controller_Front::getInstance()->getRouter();

		$route = new Zend_Controller_Router_Route(
		':lang/@seo_article_url_keyword/:alias',
		    array(
				'module' =>'article',
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
		$router->addRoute('Article_Show', $route);

	}

}