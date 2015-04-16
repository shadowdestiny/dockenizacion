<?php

class Billing_PayController extends Zend_Controller_Action
{
	public $um;
	public $userData = Array();

    public function init()
    {

		if(!Zend_Auth::getInstance()->hasIdentity())
		{
			$this->redirect($this->view->url(Array(),"User_Login"));
		}

		if(!hasAccess("pay")){
			$this->redirect($this->view->Url(Array(),"NoAccess",true));
		}

		$this->um = new User_Manager();
		$this->userData = Zend_Registry::get("user_data");
		$this->view->userData = $this->userData;
	}

	public function indexAction()
	{

		$req = $this->getRequest();
		if ($req->isPost())
		{

			//print_r($req->getParams());exit;
			//exit;
		}
/*


		//  Options for billers
		$arrBillingOptions = Array(

		);
		$this->view->billingOptions = $arrBillingOptions;


		/*
		$order_id=6;
		$obj_o=new Logic_Order();
		$arrOrderResult = $obj_o->processOrder($order_id);
		print_r($arrOrderResult);
		exit;
		*/

		if ( $this->userData['verified_email']==0 )
		{

			$this->redirect($this->view->url(Array()),"Billing_Cart");
		}

		$c = new Zend_Currency("de_DE");

		if( !$this->um->hasCompleteProfile( $this->userData['user_id'] ) )
		{
			// there are missing some needed values
			$this->forward("completeprofile","account","user");
		}
		else
		{
			// check cases

			$request = $this->getRequest();

			$obj_c = new Logic_Cart();
			if($obj_c->hasCartId())
			{
				$cart_id = $obj_c->getCartId();
				$this->view->cart_id = $cart_id;

				$this->view->items = $obj_c->getCartItems($cart_id);

				$this->view->cart_price = $obj_c->getTotalPrice($cart_id);

				if(
					$this->view->cart_price<10
					&& $request->getParam("biller","") <> "account"
				)
				{
					$this->view->total_price = ($this->view->cart_price + 0.35);
					$this->view->transaction_fee = "0.35";

				} else {
					$this->view->total_price = ($this->view->cart_price );
					$this->view->transaction_fee = "0";
				}

				$this->view->biller = $request->getParam("biller","");
				$this->view->billing_type = $request->getParam("billing_type","");

if($request->isPost())
{
print_r($_POST);
exit;
}
				// show summary
				if($request->isPost() && $request->getParam("act")=="submit_index")
				{

					$this->render("summary");
				}
				elseif($request->isPost() && $request->getParam("act")=="submit_summary")
				{


					if($cart_id == $request->getParam("cart_id"))
					{
						// Billing types
						// deposit
						if($request->getParam("billing_type","") == "deposit")
						{
//exit("1");
							//exit;
							$this->view->total_price = $request->getParam("amount_payin",0);
							$this->render("simulatedeposit");
						}
						elseif($request->getParam("billing_type","") == "direct")
						{
//exit("2");
							switch($request->getParam("biller",""))
							{
								case "mycc1":
									//$this->forward("mycc1","pay","billing");
									break;
								case "account":

									//$this->forward("account","pay","billing");
									break;
								case "sofort":
									//$this->forward("sofort","pay","billing");
									break;
								case "paysavecard":
									//$this->forward("paysavecard","pay","billing");
									break;
								case "paysavecard":
									//$this->forward("paysavecard","pay","billing");
									break;
								case "skrill":
									//$this->forward("skrill","pay","billing");
									break;
								default:
									//$this->forward("success","pay","billing");

	//								$this->redirect( $this->view->url( Array("module"=>"billing","controller"=>"pay","action"=>"success","lang"=>Settings::get("S_lang"),"biller"=>$request->getParam("biller"),"cart_id"=>$cart_id),"defaultRoute",true) );

									break;
							}

							$this->render("simulatedirect");
						}
						elseif($request->getParam("billing_type","") == "account")
						{

							$this->redirect($this->view->Url(Array(),"Billing_Buy"));
						}
						else
						{
							exit("error 144");
						}
					}
					else
					{
						exit("error 145");
					}

				}
				elseif($request->getPost("act") == "simulate")
				{

					// pay
					$biller = $request->getParam("biller");
					$billing_type = $request->getParam("billing_type");
					$amount = $request->getParam("amount",0);
					$cart_id = $request->getParam("cart_id",0);
					$user_id = $this->userData['user_id'];

					if ( $billing_type=="deposit")
					{
						$obj_b = new Logic_Billing($user_id);
						$billiner_transaction_id = $biller."_".time().rand(1000,9000);

						$obj_o = new Logic_Order();
						$obj_t = new Billing_Model_Transactions();

						$arrOrderResult=Array();

						$transaction_id = $obj_t->addTransaction("payin",$user_id,$amount,$biller,$billiner_transaction_id);
					}

					$this->redirect( $this->view->Url(Array("module"=>"billing","controller"=>"pay","action"=>"process"),"defaultRoute") );

					if( $biller == "deposit")
					{
exit("A");
						if($this->view->userData['budget'] > $amount )
						{

							//$amount = ($amount*-1);

							//$transaction_id = $obj_t->addTransaction("pay_order",$user_id,$amount,$biller,time());

							echo "cart_id: ".$cart_id."<br>";
							$order_id = $obj_o->changeCartToOrder($cart_id);
							echo "order id: ".$order_id."<br>";
							//$obj_t->setOrderIdToTransactionId($transaction_id,$order_id);

							$arrOrderResult = $obj_o->processOrder($order_id);
							print_r($arrOrderResult);
							exit;

							$this->render("successfull");

						}
						else
						{
							// mfg log
							exit("error");
						}

					}
					else
					{

/*
		$amount=10;
		$biller = "paysafecard";

		$user_id = $this->userData['user_id'];
		echo $this->view->userData['budget'];
		$obj_b = new Logic_Billing($user_id);
		$biller = "paysafecard";
		$transaction_id = time();

		$obj_b->payIn($user_id,$amount,$biller,$transaction_id);

		//echo $user_id;
		exit;
		*/

						$transaction_id = $obj_b->payIn($user_id,$amount,$biller,time());
						//echo $transaction_id;
						$this->render("successfull");
					}



				}
				elseif($request->getPost("act") == "simulate_done")
				{

					header("Location: ".$this->view->Url(Array(),"Billing_Buy"));
					exit;
				}
				else
				{

				}
			}
			else
			{
				// mfg error
				exit;
				$this->redirect("/");
			}
		}

		/*
		$c = new Zend_Currency("de_DE");
		$c->setValue("12.00");

		//$c->setFormat(array('currency' =>  'EUR', 'name' =>'EUR', 'symbol' => '€'));
		$currency = Zend_Locale_Format::toNumber(2.35, array(
		'number_format'  =>  '$ #,##0.00 USD'));
		echo $currency;
		echo $c->toString();
		exit;
		*/
	}


	public function confirmAction()
	{
		$obj_c = new Logic_Cart();
			if($obj_c->hasCartId())
			{
				//Wenn alte Tickets vorhanden sind
				$this->view->hasOldItems = true;
				$cart_id = $obj_c->getCartId();
				$this->view->cart_id = $cart_id;

				$this->view->items = $obj_c->getCartItems($cart_id);
			}
	}

	public function processAction()
	{

		if($this->view->userData['budget'] > $amount )
		{

		}
		exit("process");
	}

	public function buyAction()
	{
		$request = $this->getRequest();
		$obj_b = new Logic_Billing($user_id);

		if($request->isPost())
		{

			if($obj_b->getCurrentBudget($user_id)>=$amount)
						{
						exit;
							$order_id = $obj_o->changeCartToOrder($cart_id,$transaction_id);
							$obj_t->setOrderIdToTransactionId($transaction_id,$order_id);

							$arrOrderResult = $obj_o->processOrder($order_id);
						} else {
							exit("not enough money payed in!");

						}
						//$transaction_id = $obj_t->addTransaction("payin",$user_id,$amount,$biller,time());

						//$amount = ($amount*-1);


		}

	}

	public function successAction()
	{
	exit("S");
		$request = $this->getRequest();
		if($request->isPost() && $request->getParam("act") == "submit_success")
		{
			$obj_c = new Logic_Cart();
			if($obj_c->hasCartId())
			{
				$cart_id = $obj_c->getCartId();



				//if()
				exit(" payed");

			}
		}
		$this->view->biller=$request->getParam("biller");
		$this->view->cart_id=$request->getParam("cart_id");
	}



	public function payinAction()
	{
exit("payin");
		$request = $this->getRequest();

		$amount=10;
		$biller = "paysafecard";

		$user_id = $this->userData['user_id'];
		echo $this->view->userData['budget'];
		$obj_b = new Logic_Billing($user_id);
		$biller = "paysafecard";
		$transaction_id = time();

		$obj_b->payIn($user_id,$amount,$biller,$transaction_id);

		//echo $user_id;
		exit;
	}



	public function errorAction()
	{
		$this->_helper->viewRenderer->setNoRender();
		echo $this->view->render("billing/errors/error.phtml");
	}

}