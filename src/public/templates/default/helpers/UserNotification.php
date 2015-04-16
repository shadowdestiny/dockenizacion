<?
class Zend_View_Helper_UserNotification {
	public $view;

	public function UserNotification ($type = NULL, $text = NULL)
	{

		if($type !== NULL && $text !== NULL) {

			$this->view->desc = $text;
			$this->view->title = $this->view->translate('notice_' . $type . '_label') . ':';

			switch($type) {
				case "info":
					$this->view->class = 'info';
					break;
				case "error":
					$this->view->class = 'danger';
					break;
				default:
					$this->view->class = '';
					break;
			}
			return $this->view->render("global/usernotification.phtml" );
		} else {
			return "";
		}
	}

	public function setView(Zend_View_Interface $view)
	{

		$this->view = $view;
	}
}