<?php

class Application_Plugin_Layout extends Zend_Controller_Plugin_Abstract
{

	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		if( preg_match("/admin/i",$request->getControllerName()))
		{
			$template="admin";
		} else {
			$template = "lotto";
		}

		Zend_Layout::startMvc();
		$layout = Zend_Layout::getMvcInstance();
		$view = $layout->getView();

		$view->doctype('XHTML1_STRICT');


		$layout->setlayoutpath(
			array(
				APPLICATION_PATH."/../templates/".$template."/layouts/",
			));

		$view->setScriptPath(APPLICATION_PATH."/../templates/".$template."/views/");
		$view->addScriptPath(APPLICATION_PATH."/../templates/".$template."/views/".$request->getModuleName()."/");
		$view->setHelperPath(APPLICATION_PATH."/../templates/default/views/");
		$view->addHelperPath(APPLICATION_PATH."/../templates/default/views/".$request->getModuleName()."/");

		$view->setHelperPath(APPLICATION_PATH."/../templates/default/helpers/");
		$view->addHelperPath(APPLICATION_PATH."/../templates/".$template."/helpers/");
		$view->addHelperPath(APPLICATION_PATH."/../templates/".$template."/helpers/".$request->getModuleName()."/");

		$view->setFilterPath(APPLICATION_PATH."/../templates/".$template."/filters/");
		$view->addFilterPath(APPLICATION_PATH."/../templates/default/filters/");
		$view->addFilterPath(APPLICATION_PATH."/../templates/".$template."/filters/".$request->getModuleName()."/");

		if($template<>"admin")
		{
			$view->addFilter("Article");
			$view->addFilter("Lottery");
			$view->addFilter("News");

			$view->addFilter("Basic");
		}

		$view->translation_prefix = $request->getModulename()."_".$request->getControllername()."_".$request->getActionname();

	}
}
