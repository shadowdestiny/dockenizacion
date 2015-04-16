<?php

class Billing_Admin_PayoutController extends Zend_Controller_Action
{
	public $userData = Array();

    public function init()
    {
		if(!hasAccess("acp") || !hasAccess('acp_billing_payout')){
			$this->redirect($this->view->Url(Array(),"NoAccess",true));
		}

		$this->um = new User_Manager();

	}

	public function indexAction()
	{
		$obj_upba = new Billing_Model_Payout_BankAccount();
		$obj_up = new Billing_Model_Payout();

		$request = $this->getRequest();


		$page=$request->getParam('page',1);
		$type=$request->getParam('type',"new");

		$this->view->type = $type;


		$select = $obj_up->select();

		if($type=="new" || $type=="error" ||$type=="payout"){
			$select->where("state=?",$type);
			$select->order("add_date");
		}

		$paginator = Zend_Paginator::factory($select);
		$paginator->setItemCountPerPage(40);
		$paginator->setCurrentPageNumber($page);

		$this->view->pageCount = $paginator->getPages()->pageCount;
		$this->view->currentPage = $page;
		$this->view->paginator = $this->view->paginationControl(
			$paginator,
			'Sliding',
			'default/pagination/pagination.phtml'
		);

		$this->view->items = $paginator;

	}

	public function detailsAction()
	{
		$obj_upba	= new Billing_Model_Payout_BankAccount();
		$obj_up		= new Billing_Model_Payout();

		$request	= $this->getRequest();
		$payout_id	= $request->getParam('payout_id',false);

		if($payout_id)
		{
			$select = $obj_up->select();
			$select->where("payout_id=?",$payout_id);

			$payout = $obj_up->fetchRow($select);
			if($payout)
			{

				$payout = $payout->toArray();

				//print_r($payout);
				if($payout['payout_account_type']=="bank")
				{

					$select = $obj_upba->select();
					$select->where("id=?",$payout['payout_account_id']);
					$payoutAccount = $obj_upba->fetchRow($select);
					if($payoutAccount)
					{
						$this->view->bankAccount = $payoutAccount->toArray();;
					} else {
						$this->view->bankAccount = Array();
					}
				}

				$this->view->payout = $payout;

				$userData = $this->um->getUserData($payout->user_id);
				$this->view->userData = $userData;

				if($request->isPost())
				{

					$status = $request->getPost("status");

					if($status=="payout")
					{

						$userData = $this->um->getUserdata($payout['user_id']);

						if($userData['win']>=$payout['amount'])
						{


							$arrData = Array(
							"state"=>"payout",
							"payout_date"=>Zend_Date::now()->toString('yyyyMMddHHmmss'),
							"payout_by" => Zend_Auth::getInstance()->getIdentity()->user_id
							);

							try
							{
								$obj_up->update($arrData,"payout_id=".$request->getParam("payout_id"));

								$obj_t = new Billing_Model_Transactions();
								$data = Array(
								"transaction_type" => "payout_successfull",
								"transaction_date"=> time(),
								"user_id"=> $userData['user_id'],
								"customer_id"=> $userData['customer_id'],
								"amount"=>$payout['amount'],
								"order_id"=>0,
								"payout_id"=>$payout['payout_id'],
								"biller"=> "",
								"biller_transaction_id" => "",
								);

								$transaction_id = $obj_t->addTransaction($data);

								$obj_m = new Mail_Manager();
								$obj_m->setuser($userData['user_id']);
								$obj_m->payoutSuccess(Array("amount"=>$payout['amount'],"transaction_id"=>$transaction_id ) );

							} catch(EXCEPTION $e)
							{

							}
						} else {
							alert("payout admin: not enough money");
							exit("Error: Maybetry to hack our system");
						}
					} elseif($status=="error") {

						$obj_payout = new Logic_Payout();
						$ret = $obj_payout->payout($payout['user_id'],(-1*$payout['amount']));
						$this->um->resetUserData($paylut['user_id']);

						if($ret)
						{

							$arrData = Array(
							"state"=>"error",
							"payout_date"=>Zend_Date::now()->toString('yyyyMMddHHmmss'),
							"payout_by" => Zend_Auth::getInstance()->getIdentity()->user_id
							);

							try
							{
								$obj_up->update($arrData,"payout_id=".$request->getParam("payout_id"));

								$obj_m = new Mail_Manager();
								$obj_m->setuser($payout['user_id']);
								$obj_m->payoutError(Array(  ) );
							} catch(EXCEPTION $e) {
								alert("payout admin: ".$e);
							}
						}
					} elseif($status=="waiting") {
						$arrData = Array(
						"state"=>"waiting",
						"payout_date"=>Zend_Date::now()->toString('yyyyMMddHHmmss'),
						"payout_by" => Zend_Auth::getInstance()->getIdentity()->user_id
						);

						try
						{
							$obj_up->update($arrData,"payout_id=".$request->getParam("payout_id"));
							exit;
						} catch(EXCEPTION $e)
						{
							alert("payout admin: ".$e);
						}
					}

					$this->redirect($this->view->url(Array("lang"=>Settings::get("S_lang"),"module"=>"billing","controller"=>"admin_payout","action"=>"index"),null,true) );

				}

				if($type=="new" || $type=="error" ||$type=="payout"){

					$select->order("add_date");
				}
			}
		}

	}

}