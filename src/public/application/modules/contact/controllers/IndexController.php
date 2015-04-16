<?php

class Contact_IndexController extends Zend_Controller_Action
{

    public $um;
	public $userData = Array();


    public function init()
    {

		$request = $this->getRequest();
		$this->popup = $request->getParam('popup',false);
		if (!hasAccess("contact")){
			if ($this->popup){
				$notification = $this->view->Notification('notice_permission_denied',true);
				$response = Array("success" => false, "html" => $notification);
				echo json_encode($response);exit;
			} else {
				$this->redirect($this->view->Url(Array(),"NoAccess",true));
			}
		}

        $this->um = new User_Manager();
		$this->userData = Zend_Registry::get("user_data");
		$this->view->userData = $this->userData;
	}


	public function indexAction()
	{

		$obj_s = new Default_Model_Stats();

		$request = $this->getRequest();
		$this->view->popup = $this->popup;

		$form = new Contact_Form_Contact(array("isPopup" => $this->view->popup));

		if($this->view->popup){
			$form->setAction($this->view->url(Array(),'Contact_Index_Index',true));
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
		}

		debug("vorm post");

		if($request->isPost() && $request->getPost("formpost")==1)
		{
			debug("contact post");
			$post = $request->getpost();
			if($form->isValid($post))
			{
				debug("valid post data");
				$data = $form->getValues();

				try
				{
					$config = array(
					'url'=>'http://mg.em.com/support/api/http.php/tickets.json',
					'key'=>'6A53FF0EA1ED6BC63E09018907E9C026'
					);

					$data = array(
					'name' => $post['name'],
					'email' => $post['email'],
					'subject' => $post['subject'],
					//'assign to' => 'emaiId', // here i want to assign ticket.
					'message' => $post['message'],
					'ip' => $_SERVER['REMOTE_ADDR'],
					//'attachments' => array(),
					"source"=>"API"
					);

					set_time_limit(30);

					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $config['url']);
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
					curl_setopt($ch, CURLOPT_USERAGENT, 'osTicket API Client v1.7');
					curl_setopt($ch, CURLOPT_HEADER, FALSE);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Expect:', 'X-API-Key: '.$config['key']));
					curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					$result=curl_exec($ch);

					$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
					curl_close($ch);

					if ($code != 201)
					{
						alert("Error by sending new Ticket from contact form!");
					}
					else
					{
						//debug("ticket creation successfull: ".$ticket_id);
						$notification = $this->view->Notification('contact_submit_successfull',$this->view->popup );

						$ticket_id = (int) $result;
						//info("New Ticket was created for User: ".Settings::get("U_user_id"). " Ticket ID: ".$ticket_id);
					}
				} catch(EXCEPTION $e)
				{
					alert("Error by creating ticket in osTicket: ".$e);
				}
			}
			else
			{
				$form->populate($post);
			}
		}

		if(Zend_Auth::getInstance()->hasIdentity())
		{
			if($this->userData['first_name']<>"" && $this->userData['last_name']<>"")
			{
				$form->name->setValue($this->userData['first_name']." ".$this->userData['last_name']);
				//$form->name->setAttrib( "class","hidden" );
				$form->name->setAttrib( "readonly",true );
			}

			$form->email->setValue($this->userData['username']);
			//$form->email->setAttrib( "class","hidden" );
			$form->email->setAttrib( "readonly",true );
		}


		$this->view->form = $form;

		if($this->view->popup)
		{

			if ($notification){
				$html  =  $notification;
			} else {
				//$html = $this->view->partial("user/auth/login.phtml",Array("popup"=>true,"form"=>$form));
				$html = $this->view->render("contact/index/index.phtml");
			}

			$response=Array("success"=>true,"html"=>$html);
			echo json_encode($response);
			$obj_s->set("show_contact_popup");
			exit;
		} else {
			if ($notification){
				$this->view->notification = $notification;
			}
			$obj_s->set("show_contact");
		}


	}
}