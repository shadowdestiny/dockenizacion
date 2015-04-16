<?php

class User_AuthController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function loginAction()
    {
		$request = $this->getRequest();
		$popup = $request->getParam('popup',false);
		$this->view->popup = $popup;

		if(Zend_Auth::getInstance()->hasIdentity()){
			if($popup){
				$response = Array("success"=>true,"redirect"=>$this->view->url(Array(),"User_Account_Index"));
				echo Zend_Json::encode($response);
				exit;
			} else {
				$this->redirect($this->view->url(Array(),"User_Account_Index"));
			}
		} else if (!hasAccess("login")){
			if ($popup){
				$notification = $this->view->Notification('notice_permission_denied',true);
				$response = Array("success" => false, "html" => $notification);
				echo Zend_Json::encode($response);exit;
			} else {
				$this->redirect($this->view->Url(Array(),"NoAccess",true));
			}
		}

		if ($request->getParam("error",false) == true){
			$this->view->errorMessage = $this->view->Notification('user_auth_login_error');
		}


		$form = new User_Form_Login();
		$this->view->form = $form;

		$obj_s = new Default_Model_Stats();
		if($popup)
		{
			// Stats
			$obj_s = new Default_Model_Stats();
			$obj_s->set("show_login_popup");

			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();

			//$html = $this->view->partial("user/auth/login.phtml",Array("popup"=>true,"form"=>$form));
			$html = $this->view->render("user/auth/login.phtml");

			$response=Array("success"=>true,"html"=>$html);
			echo Zend_Json::encode($response);
			$obj_s->set("show_login_popup");
		} else {
			$obj_s->set("show_login");
		}
    }

	public function registrationAction()
    {

		$request = $this->getRequest();
		$popup = $request->getParam('popup',false);
		$this->view->popup = $popup;

		if(Zend_Auth::getInstance()->hasIdentity()){
			if($popup){
				$response = Array("success"=>true,"redirect"=>$this->view->url(Array(),"User_Account_Index"));
				echo Zend_Json::encode($response);
				exit;
			} else {
				$this->redirect($this->view->url(Array(),"User_Account_Index"));
			}
		} else if (!hasAccess("registration")){
			if ($popup){
				$notification = $this->view->Notification('notice_permission_denied',true);
				$response = Array("success" => false, "html" => $notification);
				echo Zend_Json::encode($response);exit;
			} else {
				$this->redirect($this->view->Url(Array(),"NoAccess",true));
			}
		}

		$obj_c=new Default_Model_Countries();
		$obj_s = new Default_Model_Stats();
		$arrCountries = $obj_c->getAllCountries("frontend");


		$request = $this->getRequest();
		$popup = $request->getParam('popup', false);
		$this->view->popup = $popup;

		$form = new User_Form_Registration(array("isPopup" => $this->view->popup));

		$form->country->setMultiOptions($arrCountries);

		if($request->isPost() && $request->getParam("formpost")){

			$obj_s->set("registration_submit");
			$data = $request->getPost();

			if( $form->isValid($data) )
			{
				$obj_s->set("registration_submit");
				$post = $form->getValues();

				$obj_u = new User_Model_User();
				$obj_ud = new User_Model_User_Details();
				$obj_ur = new User_Model_User_RegistrationDetails();

				$arrUser = Array(
					"active"=>1,
					"role"=>"user",
					"username"=>$post['email'],
					"password"=>md5($post['password']),
					"locale"=>Settings::get("S_locale"),
					"lang"=>Settings::get("S_lang"),
					"country"=>$post['country'],
					"currency"=>Settings::get("S_currency"),
					"user_code"=>md5($post['email']),
					"last_action"=>Zend_Date::now()->toString('yyyyMMddHHmmss'),
					"last_ip"=>$_SERVER['REMOTE_ADDR'],
					"budget"=>0
				);

				$user_id = $obj_u->insert($arrUser);
				//$user_id = 113695;
				if($user_id>0)
				{
					$arrRegData = Array(
					"user_id"=>$user_id,
					"registration_date"=>Zend_Date::now()->toString('yyyyMMddHHmmss'),
					"email"=>$post['email'],
					"ip"=>$_SERVER['REMOTE_ADDR'],
					"user_agent"=>$_SERVER['HTTP_USER_AGENT'],
					"player_id"=>0,
					"whitelabel"=>"intern",
					"lang"=>Settings::get("S_lang"),
					"country"=>Settings::get("S_country"),
					"currency"=>$post['country'],
					"locale"=>Settings::get("S_locale"),
					"registration_type"=>($post['popup']==1? "popup":"page")
					);
					$obj_ur->insert($arrRegData);

					$birth_date = new Zend_Date($post['birth_year']."-".$post['birth_month']."-".$post['birth_day']);
					$arrDetails = Array(
					"user_id"=>$user_id,
					"first_name"=>$post['firstname'],
					"gender"=>$post['gender'],
					"country"=>$post['country'],
					"birth_date"=>$birth_date->toString('yyyyMMddHHmmss')
					);
					$obj_ud->insert($arrDetails);

					$obj_m = new Mail_Manager();
					$obj_m->setUser($user_id);
					$params=Array(
					"first_name"=>$post['firstname'],
					"username"=>$post['email'],
					"password"=>$post['password'],
					"user_code"=>$arrUser['user_code'],
					);
					$obj_m->sendRegistration($params);


					// for account page, set values in session
					Settings::get("S_first_login",true);

					if($popup)
					{
						$response = Array("success"=>true,"redirect"=>$this->view->url(Array(),"User_Account_Index")."?uc=".$arrUser['user_code']);
						echo Zend_Json::encode($response);
						// Stats
						$obj_s->set("registration_complete_popup");
					} else {
						$obj_s->set("registration_complete");
						$this->redirect($this->view->url(Array(),"User_Account_Index")."?uc=".$arrUser['user_code']);
						// Stats

					}
					exit;
				}

			} else {
				$form->populate($data);
				// Stats
				$obj_s = new Default_Model_Stats();
				$obj_s->set("registration_error");
			}
		}
		else
		{
			$form->country->setValue(Settings::get("S_country"));
		}

		$this->view->form = $form;
		$form->setAction($this->view->url(Array(),'User_Registration',true));

		if($popup)
		{
			// Stats
			$obj_s->set("show_registration_popup");

			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();

			$html = $this->view->render("user/auth/registration.phtml");

			$response=Array("success"=>true,"html"=>$html);
			echo Zend_Json::encode($response);
		}
		else
		{
			// Stats
			$obj_s->set("show_registration");
		}

	}

	public function verifyAction()
	{
		$obj_s = new Default_Model_Stats();
		if( !Zend_Auth::getInstance()->hasIdentity() ){
			$this->redirect($this->view->url(Array(),"User_Login"));
		}

		$this->view->userData = Zend_Registry::get('user_data');

		$request = $this->getRequest();

		if($request->isPost()){
			if($request->getPost("act") == "verfifactionmail"){
				$obj_m = new Mail_Manager();
				$obj_m->setuser($this->view->userData['user_id']);
				$obj_m->sendRegistration();

				// Stats
				$obj_s = new Default_Model_Stats();
				$obj_s->set("send_email_verify_mail");
			}
		}

		$this->view->popup = $request->getParam('popup', false);

		$sendnow = $request->getParam('act', false);
		if ($sendnow == 'verfifactionmail') {
			$notification = $this->view->Notification('user_auth_verify_sendmailsuccess',$this->view->popup);
		}



		if($this->view->popup){
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();

			if ($notification){
				$html = $notification;
			} else {
				$obj_s->set("show_email_verify_popup");
				$html = $this->view->render("user/auth/verify.phtml");
			}

			$response=Array("success"=>true,"html"=>$html);
			echo Zend_Json::encode($response);

		} else {
			$obj_s->set("show_email_verify");
		}
	}

	public function changemailAction()
	{
		$request = $this->getRequest();
		$popup = $request->getParam('popup', false);
		$this->view->popup = $popup;
		$form = new User_Form_Changemail();

		if($request->isPost() && $request->getParam("formpost"))
		{

			$data = $request->getPost();
			if($form->isValid($data))
			{
				//hier die Email aktualisieren
				// @todo

				if ('success' != 'success'){
					$notification = $this->view->Notification('user_auth_changemail_success',$this->view->popup);
				} else {
					$notification = $this->view->Notification('user_auth_changemail_error',$this->view->popup);
				}

				// Stats
				$obj_s = new Default_Model_Stats();
				$obj_s->set("change_email");

			} else {
				//$this->view->Notification('user_auth_forgotpassword_emailnot');
				$form->populate($data);
			}
		}

		if($popup)
		{
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();

			if($notification) {
				$html = $notification;
			} else {
				$this->view->form = $form;
				$html = $this->view->render("user/auth/changemail.phtml");
			}



			$response=Array("success"=>true,"html"=>$html);
			echo Zend_Json::encode($response);
		}

	}

	public function activateAction()
	{
		$request = $this->getRequest();
		$user_code=$request->getParam("user_code","");
		if($user_code<>"")
		{
			$obj_u= new User_Model_User();
			$select = $obj_u->select();
			$select->where("user_code=?",$user_code);
			$select->where("active=?",1);
			$select->where("verified_email=?",0);
			$user=$obj_u->fetchRow($select);
			if($user)
			{
				$user->verified_email=1;
				$user->last_action=Zend_Date::now()->toString('yyyyMMddHHmmss');
				$user->last_ip=$_SERVER['REMOTE_ADDR'];
				$obj_u->update($user->toArray(),"user_id=".$user->user_id);

				$um = new User_Manager($user->user_id);
				$um->resetuserData($user->user_id);

				$obj_m = new Mail_Manager();
				$obj_m->setuser($user->user_id);
				$obj_m->sendWelcome();

				// Stats
				$obj_s = new Default_Model_Stats();
				$obj_s->set("user_activation");

				$this->redirect( $this->view->url(Array(),"User_Account_Index")."?uc=".$user->user_code );
				exit;
			} else {
				$this->redirect($this->view->Url( Array(),"User_Account_Index" ));
			}
		} else {
			$this->redirect("/");
		}
		exit;
	}


	public function forgotpasswordAction()
    {

		$request = $this->getRequest();
		$form = new User_Form_Forgotpassword();
		$this->view->popup = $request->getParam('popup', false);
		if($request->isPost() && $request->getParam("formpost"))
		{
			$data = $request->getPost();
			if($form->isValid($data))
			{
				$post = $form->getValues();

				$obj_u = new User_Model_User();
				$select = $obj_u->select();
				$select->where("username=?",$post['email']);
				$select->where("active=?",1);
				$user = $obj_u->fetchRow($select);

				if($user)
				{

					$um = new User_Manager($user->user_id);
					//$userData = $um->getUserData($user->user_id));

					$new_pw = $um->generatePassword( 12,12,true );

					$user->tmp_password = $new_pw;

					if($obj_u->update($user->toArray(),"user_id=".$user->user_id))
					{
						// Mail senden
						$obj_m = new Mail_Manager($user->user_id);
						$obj_m->setUser($user->user_id);
						$obj_m->sendForgotPassword($new_pw);
						$notification = $this->view->Notification('user_auth_forgotpassword_success',$this->view->popup);

						// Stats
						$obj_s = new Default_Model_Stats();
						$obj_s->set("set_tmp_password");
					}
					else
					{
						$notification = $this->view->Notification('user_auth_forgotpassword_error',$this->view->popup);
					}
				}
			} else {
				//$this->view->Notification('user_auth_forgotpassword_emailnot');
				$form->populate($data);
			}
		}

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
				$html = $this->view->render("user/auth/forgotpassword.phtml");
			}

			$response=Array("success"=>true,"html"=>$html);
			echo Zend_Json::encode($response);

		} else {
			$this->view->notifyMessage = $notification;
		}

		// Stats
		$obj_s = new Default_Model_Stats();
		$obj_s->set("forgot_password");
	}

	public function resetpasswordAction()
	{

		$request = $this->getRequest();
		$this->view->is_mail = true;

		$form = new User_Form_Newpassword(array("isPopup" => false));

		if($request->isPost() && $request->getParam("formpost"))
		{
			$data = $request->getPost();

			if($form->isValid($data))
			{
				$user_code = $request->getParam("user_code","");
				if($user_code<>"")
				{
					$obj_u = new User_Model_User();
					$select = $obj_u->select();
					$select->where("user_code=?",$user_code);
					$select->where("active=?",1);
					$select->where("tmp_password<>''");

					$user = $obj_u->fetchRow($select);

					if($user)
					{
						$post = $form->getValues();

						$user->password = md5($post['password']);
						$user->tmp_password = "";
						$obj_u->update($user->toArray(),"user_id=".$user->user_id);

						// Stats
						$obj_s = new Default_Model_Stats();
						$obj_s->set("changed_password");

						$this->redirect($this->view->url(Array(),"User_Login"));
						exit;
					}
				}
			}
			else
			{
				$form->populate($data);
			}
		}

		$this->view->form = $form;

	}

	public function oldusersAction()
	{
		$form = new User_Form_Oldusers();
		$this->view->form = $form;
	}
}