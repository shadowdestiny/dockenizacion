<?php

class Twitter_Form_Decorator_Errors extends Zend_Form_Decorator_Errors
{
	public function render($content)
	{
		$element = $this->getElement();
		$view = $element->getView();

		if (null === $view) {
				return $content;
		}

		$errors = $element->getMessages();

		if (empty($errors)) {
				return $content;
		}

		$element->setAttrib("class", trim("has-error " . $element->getAttrib("class")));

		$wrapper = $element->getDecorator("outerwrapper");
		if($wrapper)
		{
			$wrapper->setOption("class", trim("has-error " . $wrapper->getOption("class")));
		}

		$separator = $this->getSeparator();
		$placement = $this->getPlacement();
		$errorHtml = '<span class="help-block"><ul>';

		foreach($errors as $currentError)
		{
			$errorHtml .= '<li>'. strip_tags($currentError) .'</li>';
		}
		$errorHtml .= '</ul></span>';
		//$errors    = $view->formErrors($errors, $this->getOptions());

		switch ($placement) {
				case self::APPEND:
						return $content . $separator . $errorHtml;
				case self::PREPEND:
						return $errorHtml . $separator . $content;
		}
	}
}
