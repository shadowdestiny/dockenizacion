<?php

class Cron_MailqueueController extends Zend_Controller_Action
{
	private $obj_m;
	
    public function init()
    {
		$this->obj_m = new Mail_Manager();
    }
		
	
	private function getItems($num=1,$priority=1)
	{
		$obj_mq = new Default_Model_Mail_Queue();
		
		$select1 = $obj_mq->select();
		$select1->from("mail_queue","id");
		$select1->where("status=?","new");
		$select1->where("priority=?",$priority);
		$select1->order("id");
		$select1->limit($num);
		$data = $obj_mq->fetchAll($select1);
		
		$arrList = Array();
		if($data->count()>0)
		{
			foreach($data as $item)
			{
				$arrList[]=$item->id;
			}
		}
		return $arrList;
	}
	
	/*
		sends mails from the mail queue to real mail accounts
	*/
	public function processAction()
	{
		$obj_mq = new Default_Model_Mail_Queue();
		
		$prio1=$this->getItems(20,1);
		$prio2=$this->getItems(10,2);
		$prio3=$this->getItems(10,3);
		
		$doProcess=false;
		
		$select = $obj_mq->select();
		if(count($prio1)>0)
		{
			$select->where("id in (?)",$prio1);
		}
		if(count($prio2)>0)
		{
			$select->where("id in (?)",$prio2);
		}
		if(count($prio3)>0)
		{
			$select->where("id in (?)",$prio3);
		}

		$select->limit(20);
		$select->order("id");
		
		$data = $obj_mq->fetchAll($select);
		if( count($data)>0)
		{
			$doProcess=true;
			$arrList = Array();
			foreach($data as $item)
			{
				$arrList[] = $item->id;
			}
			
			$db = $obj_mq->getAdapter();
			$db->exec("update mail_queue set Status='starting_php' where status='new' and id in(".implode(",",$arrList).")");
		}
		
		if( $doProcess )
		{				
			$select = $obj_mq->select();
			$select->where("status=?","starting_php");
			$select->order("id");
			$select->limit(20);
		
			$data = $obj_mq->fetchAll($select);
			
			foreach($data as $item)
			{
				$obj_mq->update(Array("status"=>"sending","last_try"=>Zend_Date::now()->toString('yyyyMMddHHmmss')),"id=".$item->id);

				if($this->sendmail($item->id))
				{
					$obj_mq->update(Array("status"=>"done","last_try"=>Zend_Date::now()->toString('yyyyMMddHHmmss')),"id=".$item->id);
				} else {
					$obj_mq->update(Array("status"=>"error","last_try"=>Zend_Date::now()->toString('yyyyMMddHHmmss')),"id=".$item->id);
				}						
			}
		}
		
		exit("DONE");
	}
	
	public function processitemAction()
	{	
		$request = $this->getRequest();
		$id = $request->getParam("id",0);
		
		//echo "OK";
		//exit;
		if($id>0)
		{		
			if( $this->sendmail($id) )
			{
				echo "OK";
				info("Sending of Mail ".$id." was successfully");
			} else {
				echo "ERROR";
				alert("Error by sending of mail ".$id);
			}
		}
		exit;
	}
	
	public function sendmail($id)
	{
		
		//return true;
		
		$this->userData['lang']="de";
		
		$obj_mq = new Default_Model_Mail_Queue();
				
		
		if($id>0)
		{
			$data = $obj_mq->fetchRow("id=".$id);
			if($data)			
			{			
				$userData = unserialize ( $data->params);
						
				$obj_c = new Default_Model_Customer();
				$cData = $obj_c->fetchRow("customer_id=".$userData['customer_id']);
				if($cData)
				{				
				
					$customerData = $cData->toArray();
									
					$config = array('auth' => 'login',
					'username' =>"tst1234@euromillions.com",
					'password' => "gN@4z778",
					'port'=>587,
					 'ssl'      => 'tls',
					);

					$config = array('auth' => 'login',
					//'username' => "support@infodir.net",
					//'password' => "loW5-ekWbwajyIb",
					//"host"=>"infodir.net",
					'username'=>"support@infodir.net",
					'password'=>'loW5-ekWbwajyIb',
					'port'=>25,
					 //'ssl'      => 'tls',
					);
			
			
					$tr = new Zend_Mail_Transport_Smtp( "mail.infodir.net", $config);
					Zend_Mail::setDefaultTransport($tr);
						
					$mail = new Zend_Mail( "UTF-8" );
					$mail->setFrom( $customerData['mail_from'], $customerData['mail_from_name'] );
					$mail->setReplyTo( $customerData['mail_from'], $customerData['mail_from_name'] );
					$mail->addTo( $userData['username'] );
					$mail->setSubject($userData['subject']);
					$mail->setBodyHtml($data['content'],"UTF-8");
					
					switch ( $userData['lang'] ) {
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
							sleep(60);
							return true;
						} else {
							alert("Mail: Error by sending mail: ".serialize($ret));
							return false;
						}
					} 
					catch(EXCEPTION $e)
					{
					echo $e;
					exit;
						alert("mail send error: ".$e);
						return false;
					}	
				}								
				else
				{
					alert("no customer data for user ".$this->user_id);
				}
			}
			else
			{
				alert("No Data found for MailQueue ID in sendmail cron for id: ".$id);
			}
		} else {
			return false;
		}
	}
}