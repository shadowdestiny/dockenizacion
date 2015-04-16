<?php
//http://euromillions.com/login
//User Aldo
//PAssword: euromillions190

class Lottery_AdminController extends Zend_Controller_Action
{

    public function init()
    {
		if(!hasAccess("acp") || !hasAccess("acp_lottery"))
		{
			$this->redirect($this->view->Url(Array(),"NoAccess",true));
		}
    }

	public function indexAction(){
		$this->redirect($this->view->Url(Array('module' => 'lottery', 'controller' => 'admin_euromillions_results', 'action' => 'index'),false,true));
	}

}