<?

class Mail_Manager
{
	public $view;
	public $user_id=0;
	public $userData = Array();
	public $customerData = Array();
	
	public function Mail_Manager()
	{
		$this->view = new Zend_View();
		$this->view->setHelperPath(APPLICATION_PATH."/../templates/default/helpers/");
		$this->view->setFilterPath(APPLICATION_PATH."/../templates/default/filters/");
		$this->view->setScriptPath(APPLICATION_PATH."/../templates/mails/");
		
	}
	
	public function setUser($user_id)
	{	
		if($user_id>0)
		{
			$this->user_id = $user_id;
			
			$um = new User_Manager($user_id);
			$userData = $um->getuserData($this->user_id);
			if($userData)
			{				
				$this->userData = $userData;
				$obj_c = new Default_Model_Customer();
				$cData = $obj_c->fetchRow("customer_id=".$userData['customer_id']);
				if($cData)
				{				
					$this->customerData = $cData->toArray();
					
					$this->view->domain = $cData->domain;
				}								
				else
				{
					alert("no customer data for user ".$this->user_id);
				}
				
				$translate = Zend_Registry::get("Zend_Translate");				
				if ( $translate->isAvailable($this->userData['lang']) )
				{
					$translate->setLocale($this->userData['lang']);
				} else  {
					$translate->setLocale("en");
				}
			}
			else
			{
				$this->user_id=0;
			}
		} else {
			$this->user_id=0;
		}		
	}
	
	public function sendForgotPassword($new_pw)
	{
		$params = Array(
		"new_pw"=>$new_pw,
		"user_code"=>$this->userData['user_code'],
		"subject"=>$this->view->Translate("mail_forgot_password_subject"),
		);

		return $this->sendMail("forgotpassword",$params);
	}
	
	public function sendWelcome()
	{		
		$params['subject'] = $this->view->Translate("mail_welcome_subject");

		return $this->sendMail("welcome",$params);
	}
	
	public function sendRegistration($params=Array())
	{		
		$params = array_merge($params,$this->userData);
		$params['subject'] = $this->view->Translate("mail_welcome_subject");

		return $this->sendMail("registration",$params);
	}
	
	
	public function sendJackpotReminder($params)
	{			
		$params = array_merge($params,$this->userData);
		$params['subject'] = $this->view->Translate("mail_jackpot_reminder_subject");

		return $this->sendMail("nextjackpots",$params);
	}
	
	
	public function payoutSuccess($params=Array())
	{		
		$params = array_merge($params,$this->userData);
		$params['subject'] = $this->view->Translate("mail_welcome_subject");

		return $this->sendMail("payout-success",$params);
	}
	
	public function payoutError($params=Array())
	{		
		$params = array_merge($params,$this->userData);
		$params['subject'] = $this->view->Translate("mail_welcome_subject");

		return $this->sendMail("payout-error",$params);
	}
	
	private function sendMail($type="",$params=Array())
	{
	
		if ( file_exists(APPLICATION_PATH."/../templates/mails/".$type.".phtml"))
		{
			if($this->user_id>0)
			{
				$msg =  $this->view->partial($type.".phtml",$params);
				
				
				$arrData = Array(
				"type"=>$type,
				"to"=>$params['username'],
				"params"=>serialize($params),
				"content"=>$msg,
				"added_on"=>Zend_Date::now()->toString('yyyyMMddHHmmss')
				);

				$obj_mq = new Default_Model_Mail_Queue();
				$obj_mq->insert($arrData);
				return true;
				
				$config = array('auth' => 'login',
						'username' =>"tst1234@euromillions.com",
						'password' => "gN@4z778",
						'port'=>587,
						 'ssl'      => 'tls',
						);
			
				$tr = new Zend_Mail_Transport_Smtp( "mail.euromillions.com", $config);
				//Zend_Mail::setDefaultTransport($tr);
				
				$mail = new Zend_Mail( "UTF-8" );
				$mail->setFrom( $this->customerData['mail_from'], $this->customerData['mail_from_name'] );
				$mail->setReplyTo( $this->customerData['mail_from'], $this->customerData['mail_from_name'] );
				$mail->addTo( $this->userData['username'] );
				$mail->setSubject($params['subject']);
				//$mail->setBodyText($msg,"UTF-8");
				$mail->setBodyHtml($msg,"UTF-8");
				
				
				switch ( $this->userData['lang'] ) {
					case "de";
						$mail->addHeader("Content-Language", "de_DE");
						$mail->addHeader("Accept-Language", "de_DE");
						break;
					case "en";
						$mail->addHeader("Content-Language", "en_GB");
						$mail->addHeader("Accept-Language", "en_GB");
						break;
					case "es";
						$mail->addHeader("Content-Language", "es_ES");
						$mail->addHeader("Accept-Language", "es_ES");
						break;
				}
				
				try
				{
					$ret = $mail->send($tr);
					if($ret)
					{
						return true;
					} else {
						alert("Mail: Error by sending mail: ".serialize($ret));
						return false;
					}
				} 
				catch(EXCEPTION $e)
				{
					alert("mail send error: ".$e);
					return false;
				}		
			} else {
				alert("mail send without user_id: ".$type." params: ".serialize($params));
				return false;
			}
		} else {
			alert("mail template not found: ".$type);
			return false;
		}
	}	
}
?>