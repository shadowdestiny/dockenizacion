<?php

class User_AccountController extends Zend_Controller_Action
{
	public $um;
	public $userData = Array();


    public function init()
    {
        $this->um = new User_Manager();
		$this->userData = Zend_Registry::get("user_data");
		$this->view->userData = $this->userData;

		$request = $this->getRequest();
		$this->popup = $request->getParam('popup',false);
		if(!Zend_Auth::getInstance()->hasIdentity()){
			if($this->popup){
				$response = Array("success"=>false,"redirect"=>$this->view->url(Array(),"User_Login"));
				echo json_encode($response);
				exit;
			} else {
				$this->redirect($this->view->url(Array(),"User_Login"));
			}
		} else if (!hasAccess("user_account")){
			if ($this->popup){
				$notification = $this->view->Notification('notice_permission_denied',true);
				$response = Array("success" => false, "html" => $notification);
				echo json_encode($response);exit;
			} else {
				$this->redirect($this->view->Url(Array(),"NoAccess",true));
			}
		}
    }

    public function indexAction()
    {
		// payout successfull
		if(Settings::get("S_payout_successfull"))
		{
			Settings::set("S_payout_successfull","");
			$this->view->payout_success=true;
		}

		$request = $this->getRequest();

    }

	public function bankingAction(){
		$request = $this->getRequest();
		$this->view->popup = $request->getParam('popup', false);
		$notification = false;

		if($this->view->popup){
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();

			if($notification){
				$html = $notification;
			}else{
			//	$this->view->form = $form;
				$html = $this->view->render("user/account/banking.phtml");
			}

			$response=Array("success"=>true,"html"=>$html);
			echo json_encode($response);
		}
	}

	public function profilesettingsAction(){ //AJR Only!

		$request = $this->getRequest();
		$this->view->popup = $request->getParam('popup', false);

		$obj_c = new Default_Model_Countries();
		$arrCountries = $obj_c->getAllCountries("frontend");
		$personalForm = new User_Form_Myaccount_Personal();
		$personalForm->setAction($this->view->url(Array(), 'User_Account_Profilesettings', true));
		$personalForm->country->setMultiOptions($arrCountries);
		$notification = false;

		if($request->isPost() && $request->getParam('formpost',false)){
			//validate form
			$data = $request->getPost();
			if($personalForm->isValid($data)){

				$post = $personalForm->getValues();

				$arrData = Array(
					"first_name"=>$post['first_name'],
					"last_name"=>$post['last_name'],
					"street"=>$post['street'],
					"zip"=>$post['zip'],
					"city"=>$post['city'],
					"country"=>$post['country'],
				);

				$obj_ud = new User_Model_User_Details();
				$obj_ud->update($arrData,"user_id=".$this->userData['user_id']);
				$this->um->resetUserData($this->userData['user_id']);

				$notification = $this->view->Notification('notice_data_updated_successfull');
			} else {
				//errors == Form errors
			}

		} else {
			$personalForm->populate($this->userData);
		}

		$this->view->info = $notification;
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$this->view->form = $personalForm;
		$html = $this->view->render("user/account/profilesettings.phtml");

		$response=Array("success"=>true,"html"=>$html);
		echo json_encode($response);
	}

	public function settingsAction(){ //AJR Only!

		$request = $this->getRequest();
		$this->view->popup = $request->getParam('popup', false);
		$this->view->amountList = Array("15000000","30000000","50000000","75000000","100000000");
		$notification = false;

		if($request->isPost() && $request->getParam("formpost"))
		{

			$post = $request->getPost();
			$obj_ud = new User_Model_User_Details();

			$arrData = Array();

			if(isset($post['newsletter']))
			{
				if($post['newsletter']==1)
				{
					$arrData['newsletter']=1;
				} else {
					$arrData['newsletter']=0;
				}
			}

			if(isset($post['jackpot_reminder']))
			{
				if($post['jackpot_reminder']==1)
				{
					if(in_array($post['jackpot'],$this->view->amountList))
					{
						$arrData['jackpot']=$post['jackpot'];
					} else {
						$arrData['jackpot']=0;
					}
				} else {
					$arrData['jackpot']=0;
				}
			}
			else
			{
				$arrData['jackpot']=0;
			}

			$obj_ud->update($arrData,"user_id=".$this->userData['user_id']);

			$this->um->resetUserData($this->userData['user_id']);

			$this->userData = $this->um->getUserData($this->userData['user_id']);
			Zend_Registry::set("user_data",$this->userData);
			$this->view->userData = $this->userData;

			$notification = $this->view->Notification('notice_data_updated_successfull');

			if(!$this->view->popup && 1 == 2)
			{
				$this->redirect( $this->view->url(Array(),"User_Account_Index") );
				exit;
			}
		}

		$this->view->newsletter = $this->userData['newsletter'];
		$this->view->jackpot_amount = $this->userData['jackpot'];

		$this->view->info = $notification;

		if($this->view->popup)
		{
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();

			$html = $this->view->render("user/account/settings.phtml");


			//echo $html;
			//exit;

			$response=Array("success"=>true,"html"=>$html);
			echo json_encode($response);
		}
	}

	// From billing page, if profile not complete
	public function completeprofileAction()
	{
		$obj_c=new Default_Model_Countries();
		$arrCountries = $obj_c->getAllCountries("frontend");

		$form = new User_Form_Completeprofile();
		$form->country->setMultiOptions($arrCountries);

		$request = $this->getRequest();
		if ( $request->isPost())
		{
			$data = $request->getPost();
			if($form->isValid($data))
			{
				$post = $form->getValues();

				$arrData = Array(
				"first_name"=>$post['first_name'],
				"last_name"=>$post['last_name'],
				"street"=>$post['street'],
				"zip"=>$post['zip'],
				"city"=>$post['city'],
				"country"=>$post['country'],
				"terms"=>$post['term'],
				);

				$obj_ud = new User_Model_User_Details();
				$obj_ud->update($arrData,"user_id=".$this->userData['user_id']);

				$this->um->resetUserData($this->userData['user_id']);

				$this->redirect( $this->view->url(Array(),"Billing_Pay") );
				exit;
			} else {
				$form->populate($data);
			}
		}
		else
		{
			$userData = Zend_Registry::get("user_data");
			$form->populate($userData);
		}

		$this->view->form = $form;

	}

	public function changepasswordAction()
	{

		$request = $this->getRequest();
		$popup = $request->getParam('popup', false);
		$this->view->popup = $popup;
		$this->view->is_mail = false;

		$form = new User_Form_Myaccount_Changepassword(array("isPopup" => $this->view->popup));
		$tmppw = md5($request->getParam('tmppassword', false));

		if($request->isPost() && $request->getParam("formpost"))
		{
			$data = $request->getPost();
			if($form->isValid($data)) {
				if ($tmppw != $this->userData['password']){
					$form->tmppassword->setErrors(array('error' => 'The password is not Correct'));
					$form->populate($data);
				} else {

					$arrInsert = Array(
						'password' => md5($data['password'])
					);
					$obj_ud = new User_Model_User();
					$obj_ud->update($arrInsert,"user_id=".$this->userData['user_id']);

					$this->um->resetUserData($this->userData['user_id']);

					$notification = $this->view->Notification('user_account_changepassword_success',$popup );
				}
			} else {
				$form->populate($data);
			}
		}
		$form->setAction($this->view->url(Array(),'User_Changepassword',true));
		$this->view->form = $form;

		if($this->view->popup)
		{

			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();

			if($notification)
			{
				$html = $notification;
			}
			else
			{
				$this->view->form = $form;
				$html = $this->view->render("user/account/changepassword.phtml");
			}

			$response=Array("success"=>true,"html"=>$html);
			echo json_encode($response);

		}

	}


}