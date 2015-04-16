<?php

class AdminController extends Zend_Controller_Action
{

    public function init()
    {
		if(!hasAccess("acp"))
		{
			$this->redirect($this->view->Url(Array(),"NoAccess",true));
		}
    }

    public function indexAction() {
//echo "lol";
        // action body
	}

	public function helpAction()
	{
		$this->_helper->layout->disableLayout();
	}

}