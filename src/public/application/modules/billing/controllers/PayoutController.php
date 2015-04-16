<?php

class Billing_PayoutController extends Zend_Controller_Action
{
	public $userData = Array();

    public function init()
    {
		$request = $this->getRequest();
		$this->popup = $request->getParam('popup',false);
		if (!Zend_Auth::getInstance()->hasIdentity()){
			if ($this->popup){
				$this->redirect($this->view->url(Array(),"User_Login") . '?popup=1');
			} else {
				$this->redirect($this->view->url(Array(),"User_Login"));
			}
		} else if (!hasAccess("payout")){
			if ($this->popup){
				$notification = $this->view->Notification('notice_permission_denied',true);
				$response = Array("success" => false, "html" => $notification);
				echo Zend_Json::encode($response);exit;
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

		$obj_p = new Billing_Model_Payout();
		$obj_s = new Default_Model_Stats();

		$select = $obj_p->select();
		$select->where("user_id=?",$this->userData['user_id']);
		$select->where("state=?","new");
		$exists = $obj_p->fetchRow($select);
		if($exists)
		{
			$this->view->status="waiting";
			$this->view->payout = $exists->toArray();
		} else {

			$this->view->popup = $this->popup;
			$this->view->minimum = '25.00';
			$this->view->winnings = $this->view->userData['win'];
			$request = $this->getRequest();
			if($request->isPost())
			{
				if (
					$this->view->minimum <= $request->getPost("amount_payout")
					&& $this->view->winnings >= $request->getPost("amount_payout")
					&& $request->getPost("account_id")>0
				)
				{
					$obj_t = new Billing_Model_Transactions();

					$transaction_id = $obj_t->addTransaction(Array(
					"transaction_type" => "payout",
					"transaction_date"=>time(),
					"user_id"=>$this->userData['user_id'],
					"customer_id"=>$this->userData['customer_id'],
					"amount"=>$request->getPost("amount_payout",0),
					"order_id"=>0,
					"payout_id"=>0,
					"biller"=> "",
					"biller_transaction_id" => "",
					)
					);

					if( $transaction_id )
					{
						$obj_payout = new Logic_Payout();
						$ret = $obj_payout->payout($this->userData['user_id'],$request->getPost("amount_payout",0));
						$this->um->resetUserData( $this->userData['user_id'] );

						if($ret)
						{
							$arrData = Array(
							"user_id"=>$this->userData['user_id'],
							"state"=>"new",
							"payout_account_type"=>"bank",
							"payout_account_id"=>$request->getPost("account_id"),
							"amount"=>$request->getPost("amount_payout"),
							"transaction_id"=>$transaction_id,
							"add_date"=>Zend_Date::now()->toString('yyyyMMddHHmmss')
							);


							try
							{
								$payout_id = $obj_p->insert($arrData);
								$obj_t->update(Array("payout_id"=>$payout_id),"transaction_id=".$transaction_id);

								Settings::set("S_payout_successfull",true);
								$this->redirect($this->view->url(Array(),"User_Account_Index"));

								exit;
							} catch(EXCEPTION $e) {							echo $e;
							echo $e;
							exit;
								alert($e);
								$this->redirect("/");

							}
						}
					}
					else
					{
						alert("error by storing of payout: ".serialize($_POST));
					}
				}
			}


			// check bank account
			$obj_pb = new Billing_Model_Payout_BankAccount();
			$select = $obj_pb->select();
			$select->where("user_id=?",$this->userData['user_id']);
			$select->where("state=?","active");
			$select->order("id desc");
			$bankAccounts  = $obj_pb->fetchAll($select);

			if($bankAccounts->count()>0)
			{
				$this->view->bankAccounts = $bankAccounts->toArray();
				$this->view->payoutaccount = true;
			} else {
				$this->view->bankAccounts =  Array();
				$this->view->payoutaccount = false;
			}

	//print_r($_POST);
	//exit;
			if($this->popup){
				// Stats
				$obj_s->set("show_payout_popup");

				$this->_helper->layout->disableLayout();
				$this->_helper->viewRenderer->setNoRender();

				$html = $this->view->render("billing/payout/index.phtml");

				$response=Array("success"=>true,"html"=>$html);
				echo Zend_Json::encode($response);
				$obj_s->set("show_login_popup");
			} else {
				$obj_s->set("show_payout");
			}
		}
	}

	public function deletebankaccountAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$request = $this->getRequest();
		$force = $request->getParam('a',false);
		$account_id = $request->getParam('account_id',false);
		if($account_id)
		{
			if($force){
				try
				{
					$obj_pb = new Billing_Model_Payout_BankAccount();
					$arrData['state']='deleted';
					$obj_pb->update($arrData,"id=".$account_id." and user_id=".$this->userData['user_id']);
					$html = $this->view->Notification('notice_delete_bank_account_ok',true);
				} catch(EXCEPTION $e)
				{
					alert("error by deleting bank account: ".$e);
					exit;
				}
			} else {
				$data = Array(
					'href' => '/en/billing/payout/deletebankaccount/',
					'account_id' => $account_id,
					'a'	=> 'y'
				);
				$html = $this->view->Notification('question_delete_bank_account',true, $data);
			}
		} else {
			$html = $this->view->Notification('',true);
		}
		$response=Array("success"=>true,"html"=>$html);
		echo Zend_Json::encode($response);


	}

	public function accountAction(){
		$obj_s = new Default_Model_Stats();

		$request = $this->getRequest();
		$step = 1;

		$this->view->popup = $this->popup;

		$obj_c = new Default_Model_Countries();
		$this->view->ibancountries = $obj_c->getAllPayoutCountries();

		$arrIbanOnly = Array();
		foreach($this->view->ibancountries as $cid => $arrCountry){
			if($arrCountry['iban_mandatory'] == 1){
				$arrIbanOnly[] = $cid;
			}
		}
		$this->view->ibanonly = Zend_Json::encode($arrIbanOnly);

		$accountForm = new Billing_Form_Bankaccount(array("isPopup" => $this->popup));

		$arrCountries = getSortedCountryList();
		$accountForm->country->setMultiOptions($arrCountries);
		$accountForm->country->setValue($this->userData['country']);

		$accountForm->accountholder->setvalue($this->userData['first_name']." ".$this->userData['last_name']);


		if($request->isPost() && $request->getParam("formpost"))
		{

			$post = $request->getPost();

			$validIban = new Tpl_Validator_Iban(Array("country"=>$post['country']));
			$validIban->setCountry("DE");

			$validBic = new Tpl_Validator_Bic();
			$validBic->setCountry($post['country']);

			$accountForm->iban->addValidator( $validIban );
			$accountForm->bic->addValidator( $validBic );

			// strip spaces
			$post['iban'] = str_replace(" ","",$post['iban']);
			$post['bic'] = str_replace(" ","",$post['bic']);

			if($accountForm->isValid($post) && $post['step']>1)
			{

				$step = $request->getpost("step",1);

				$obj_pb = new Billing_Model_Payout_BankAccount();

				$data = $accountForm->getValues();

				$accountForm->populate($data);

				$accountForm->iban->setAttrib("disable",true);
				$accountForm->accountnumber->setAttrib("disable",true);
				$accountForm->bankname->setAttrib("disable",true);
				$accountForm->country->setAttrib("disable",true);
				$accountForm->bic->setAttrib("disable",true);

				$select=$obj_pb->select();
				if($post['accountnumber']<>"")
				{
					$select->where("account_number=?",$post['accountnumber']);
					$select->where("bic=?",$post['bic']);

					$accountForm->RemoveElement("iban");
					$accountForm->iban->setAttrib("disable",true);

				} else {
					$select->where("iban=?",$post['iban']);
					$select->where("bic=?",$post['bic']);

					$accountForm->RemoveElement("accountnumber");
				}

				$select->where("state=?","active");
				$select->where("user_id=?",$this->userData['user_id']);

				$bank_account = $obj_pb->fetchRow($select);

				if(!$bank_account)
				{

					// hide labels
					$accountForm->iban_label->setValue("");
					$accountForm->account_label->setValue("");

					if($data['step']=="2")
					{
						$data['step']=3;
						$accountForm->step->setValue($data['step']);

					}
					elseif($data['step']=="3")
					{

						$arrData = Array(
						"user_id"=>$this->userData['user_id'],
						"state"=>"active",
						"account_holder"=>$this->userData['first_name']." ".$this->userData['last_name'],
						"country"=>$post['country'],
						"bank_name"=>$post['bankname'],
						"bic"=>$post['bic'],
						"iban"=>(isset($post['iban'])? $post['iban']:""),
						"account_number"=>(isset($post['accountnumber'])? $post['accountnumber']:"")
						);

						try
						{
							$obj_pb->insert($arrData);
							$notification = $this->view->Notification('billing_payout_account_added_successfull',$this->popup);

							$obj_s->set("add_bank_account");

							if(!$this->popup)
							{
								$this->redirect($this->view->Url(Array(),"Billing_Payout"));
							}
						} catch(EXCEPTION $e)
						{
						echo $e;
						exit;
							$obj_s->set("add_bank_account_error");
							alert($e);
						}
					}
				}
				else
				{
					$notification = $this->view->Notification('error_payout_account_exist',$this->popup);
				}
			} else {
				$post['step']=2;
				$accountForm->populate($post);
			}
		}

		$this->view->form = $accountForm;

		if($this->popup)
		{
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();

			if ($notification){
				$html = $notification;
			} else {
				if($step==1)
				{
					$html = $this->view->render("billing/payout/account.phtml");
				} elseif($step==2)
				{
					$html = $this->view->render("billing/payout/accountconfirm.phtml");
				}
			}

			$response=Array("success"=>true,"html"=>$html);
			echo Zend_Json::encode($response);
		} else {
			$this->view->notification = $notification;
		}
	}

	public function ibancountryAction(){

		$request = $this->getRequest();
		$this->view->popup = $this->popup;

		$obj_c = new Default_Model_Countries();
		$this->view->ibancountries = $obj_c->getAllPayoutCountries();

		if($this->popup){
			$html = $this->view->render("billing/payout/ibancountry.phtml");

			$response=Array("success"=>true,"html"=>$html);
			echo Zend_Json::encode($response);
			exit;
		}
	}


	public function errorAction()
	{
		$this->_helper->viewRenderer->setNoRender();
		echo $this->view->render("billing/errors/error.phtml");
	}

}