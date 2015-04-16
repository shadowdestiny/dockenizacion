<?

class Zend_View_Helper_Email
{
	public $view;

	public function Email()
	{
		return $this;
	}

	public function imagePath()
	{
		return 'http://mg.em.com/templates/lotto/img/emails';
	}

	public function sitename()
	{
		return 'Euromillions';
	}

	public function domain()
	{
		return 'mg.em.com';
	}

	public function url()
	{
		return 'http://mg.em.com';
	}

	public function supportMail()
	{
		return 'support@euromillions.com';
	}

	public function setView(Zend_View_Interface $view)
	{
		$this->view = $view;
	}
}