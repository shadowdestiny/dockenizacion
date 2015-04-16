<?php

class Zend_View_Helper_Formattext extends Zend_View_Helper_Placeholder_Container_Standalone
{
	public $view;

	public function __construct()
	{
		parent::__construct();
	}

	public function Formattext()
	{
		return $this;
	}

	public function short_word($word="", $len=30) {
		// hart kürzen
		// $len Zeichen wird abgeschnitten und ... gesetzt
		$order   = array("\r\n", "\n", "\r");
		$replace = '';
		$text = str_replace($order, $replace, $word);
		$text = utf8_decode($text);
		$text = trim($text);
		if(strlen($text) > $len) {
			$text_short = substr ($text,0,$len)."...";
		} else {
			$text_short = $text;
		}
		$text_short = utf8_encode($text_short);
		return $text_short;
	}

	public function short_text($text="", $len=30)
	{
		// Umbrüche müssen raus!
		$order   = array("\r\n", "\n", "\r");
		$replace = '';
		$text = str_replace($order, $replace, $text);
		$text = trim($text);
		if(strlen($text) > $len) {
			// auf $len Zeichen kürzen, mittels Tooltip komplett angezeigt
			$text_short = preg_replace("/^(.{0,".$len."}\s)(.*)$/e", "'\\1...'", $text);
			if($text == $text_short)
			{
				$text_short = substr ($text,0,$len)." ...";
			}
		} else {
			$text_short = $text;
		}
		return $text_short;
	}

	public function winners_number($number)
	{
		return $number;
	}


	public function __call($method, $args)
	{
		if (preg_match('/^(?P<action>set|(pre|ap)pend|offsetSet)(?P<type>Name|HttpEquiv)$/', $method, $matches)) {
			$action = $matches['action'];
			$type   = $this->_normalizeType($matches['type']);
			$argc   = count($args);
			$index  = null;

			if ('offsetSet' == $action) {
				if (0 < $argc) {
					$index = array_shift($args);
					--$argc;
				}
			}

			if (2 > $argc) {
				require_once 'Zend/View/Exception.php';
				$e = new Zend_View_Exception('Too few arguments provided; requires key value, and content');
				$e->setView($this->view);
				throw $e;
			}

			if (3 > $argc) {
				$args[] = array();
			}

			$item  = $this->createData($type, $args[0], $args[1], $args[2]);

			if ('offsetSet' == $action) {
				return $this->offsetSet($index, $item);
			}

			$this->$action($item);
			return $this;
		}

		return parent::__call($method, $args);
	}


	public function setView(Zend_View_Interface $view)
	{

		$this->view = $view;
	}
}
