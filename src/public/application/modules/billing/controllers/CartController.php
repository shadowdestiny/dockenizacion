<?php

class Billing_CartController extends Zend_Controller_Action
{

    public function init()
    {
		$this->view->userData = Zend_Registry::get('user_data');
	}

	public function indexAction()
	{

		if(!hasAccess("cart")){
			$this->redirect($this->view->Url(Array(),"NoAccess",true));
		}
		$obj_cart = new Logic_Cart();

		// stats
		$obj_s = new Default_Model_Stats();
		$obj_s->set("show_cart");

		if($obj_cart->hasCartId())
		{
			//Wenn alte Tickets vorhanden sind
			$this->view->hasOldItems = true;

			$cart_id = $obj_cart->getCartId();
			// get data from current cart

			$obj_cart->cleanupCart($cart_id);

			$data = $obj_cart->getCartItems($cart_id);

			$this->view->removed = false;
			$this->view->data = $data;
			//print_r($data);
			//exit;
		} else {
			$this->view->data=Array();
		}

	}



	public function removeitemfromcartAction()
	{

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		if(hasAccess("cart")){

			$request = $this->getRequest();
			if($request->isPost())
			{

				$post = $request->getPost();

				if(isset($post['id']))
				{

					$post_id = $post['id'];

					if($post_id>0)
					{

						$obj_lc = new Logic_Cart();
						if($obj_lc->hasCartId())
						{
							$cart_id = $obj_lc->getCartId();

							if( $obj_lc->delItemGroupFromCart($cart_id,$post_id) )
							{
								$cart_id = $obj_lc->getCartId();

								$this->view->removed = true;
								$data = $obj_lc->getCartItems($cart_id);
								$this->view->data = $data;

								$html = $this->view->render("billing/cart/index.phtml");

								$response = Array("success" => true, "id" => $post_id,"html"=>$html);
								echo Zend_Json::encode($response);
							} else {
								$notification = $this->view->Notification(false,true);
								$response = Array("success" => false, "html" => $notification);
								echo Zend_Json::encode($response);
							}
						} else {
							$notification = $this->view->Notification(false,true);
							$response = Array("success" => false, "html" => $notification);
							echo Zend_Json::encode($response);
						}
					}
					else
					{
						$notification = $this->view->Notification(false,true);
						$response = Array("success" => false, "html" => $notification);
						echo Zend_Json::encode($response);
					}
				} else {
					$notification = $this->view->Notification(false,true);
					$response = Array("success" => false, "html" => $notification);
					echo Zend_Json::encode($response);

				}
			//} else {
				$notification = $this->view->Notification(false,true);
				$response = Array("success" => false, "html" => $notification);
				echo Zend_Json::encode($response);
			}
		} else {
			$notification = $this->view->Notification(false,true);
			$response = Array("success" => false, "html" => $notification);
			echo Zend_Json::encode($response);

		}
		exit;
	}

	public function oldticketsAction()
	{
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		if(hasAccess("cart")){
			$request = $this->getRequest();
			$type = $request->getParam('type',false);
			if($type){
				switch($type){
					case 'delete':
						//do delete stuff
						$notification = $this->view->Notification(false,true);
						$response = Array("success" => false, "html" => $notification);
						echo Zend_Json::encode($response);
						break;
					case 'update':
						//do update stuff
						$notification = $this->view->Notification(false,true);
						$response = Array("success" => false, "html" => $notification);
						echo Zend_Json::encode($response);
						break;
					default:
						$notification = $this->view->Notification(false,true);
						$response = Array("success" => false, "html" => $notification);
						echo Zend_Json::encode($response);
						break;
				}

			} else {

			}

		}
	}
	public function testAction()
	{


		//$obj_c = new Billing_Model_Cart();

		//$cart_id = $obj_c->getCartId();
		//echo $obj_c->getTotalPrice($cart_id);
		//exit;
		//echo $cart_id;
		//exit;


		$request = $this->getRequest();
/*
		$obj_c = new Billing_Model_Cart();
		$sum = $obj_c->getTotalPrice();
		$items = $obj_c->getItems();
*/

/*
 * [cart_item_id] => 10
            [cart_id] => 2
            [type] => ticket
            [name] => Standard Ticket
            [data] => a:9:{s:7:"numbers";a:5:{i:0;s:1:"5";i:1;s:2:"14";i:2;s:2:"23";i:3;s:2:"28";i:4;s:2:"31";}s:5:"stars";a:2:{i:0;s:1:"1";i:1;s:1:"2";}s:9:"recurring";b:0;s:9:"num_draws";i:1;s:7:"tuesday";i:1;s:6:"friday";i:0;s:5:"price";s:4:"2.35";s:4:"name";s:15:"Standard Ticket";s:4:"type";s:6:"ticket";}
            [tuesday] => 1
            [friday] => 0
            [num_draws] => 1
            [price] => 2.35
            [add_date] => 2014-04-15 10:48:39
 */
/*

		if($sum>0)
		{
			$currency = Zend_Registy::get("Zend_Currency");

			//$currency->setLocale(Settings::get("S_currency"));
			$this->view->items = $items;
			$this->view->total_amount = $currency->setValue($sum,Settings::get("S_currency"));

		}

		//exit;
*/
	}
}