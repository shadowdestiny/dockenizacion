<?php

class Zend_View_Filter_Basic
{
	
	public function filter($buffer)
	{
	
		//$buffer="{{base_url}}";
		
		$layout = Zend_Layout::getMvcInstance();
		$view = $layout->getView();
				
		$search[]="{{base_url}}";
		//$replace[]=$view->baseUrl();
		$replace[]="http://".$_SERVER['HTTP_HOST'];
		
		$search[]="{{image_base_url}}";
		$replace[]=$view->baseUrl()."/templates/default/images";
		
		$buffer = str_replace($search, $replace, $buffer);
		
		return $buffer;
	}
}
?>