<?php

class Billing_BillingController extends Zend_Controller_Action
{
	public $userData = Array();


    public function init()
    {
		$this->userData = Zend_Registry::get("user_data");
		if(!Zend_Auth::getInstance()->hasIdentity())
		{
			exit("you are not logged in");
			$this->redirect($this->view->url(Array(),"User_Login"));
		}
		if(!hasAccess("billing")){
			$this->redirect($this->view->Url(Array(),"NoAccess",true));
		}
    }

	public function index2Action()
	{
		if ( $this->userData['verified']==0 )
		{

		}

	}

	public function indexAction()
	{
			//@Micha
		$obj_cart = new Logic_Cart();
		if($obj_cart->hasCartId())
		{
			$cart_id=$obj_cart->getCartId();
			$obj_order = new Logic_Order();

			$order_id = $obj_order->changeCartToOrder($cart_id,123456);
			if($order_id>0)
			{
				echo "ja orderid=".$order_id;
			} else {
				echo "nein";
			}
		}
	}

	public function errorAction()
	{
	}
}