<?
class Zend_View_Helper_Systemmessage {
	public $view;

	/**
	 *
	 * @param string $icon -> info, ask, check, star, alert, alert-red
	 * @param string $text
	 * @param string $type -> yesno, okreload, billing, limit, ok
	 * @param integer $userId
	 * @return string
	 */
	public function Systemmessage ($icon, $text, $type)
	{
		$this->view->systemType = $type;
		$this->view->systemIcon = $icon;
		$this->view->systemText = $text;
		$c = $this->view->render("global/systemmessage.phtml");
		return $c;
	}

	public function setView(Zend_View_Interface $view)
	{
		$this->view = $view;
	}
}