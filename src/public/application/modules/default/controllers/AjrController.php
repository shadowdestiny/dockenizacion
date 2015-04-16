<?php

class AjrController extends Zend_Controller_Action
{

	public function init()
	{
		$this->_helper->viewRenderer->setNoRender();
		$this->_helper->layout->disableLayout();
	}

	public function postAction()
	{

		$request = $this->getRequest();

		if(!$request->isPost()) {
			// es sind nur POST erlaubt
			error_log("AJR - es wurde kein Post übertragen (".$request->getParam('act','noSettings').")");
			exit();
		}
/*
		$fh = fopen("x.log","a+");
		fputs($fh,"POST: ".serialize($_POST)."\r\n");
		fputs($fh,"GET: ".serialize($_GET).$_SERVER['REQUEST_URI']."\r\n");
		fputs($fh,"URL: ".$_SERVER['REQUEST_URI']."\r\n");
		fputs($fh,"Referer: ".$_SERVER['HTTP_REFERER']."\r\n");
		fclose($fh);
		*/

		$action = $request->getParam('act');
/*
		$fh = fopen("ajr.log","a+");
		fputs($fh,$action." - ".serialize($_POST));
		fclose($fh);
		file_put_contents("ajr.log",$action);*/

		// Stats
		$obj_s = new Default_Model_Stats();
		$obj_s->set("ajr_post");

		switch($action) {
			case "updaterequest":
				/*
				$obj_c = new Logic_Cart();
				if($obj_c->hasCartId())
				{
					$cart_id = $obj_c->getCartId();
					$obj_c->updateCart($cart_id);
					$arrCart = array(
					'items' => $obj_c->getItemCount($cart_id),
					'amount' =>$this->view->Currency()->get($obj_c->getTotalPrice($cart_id) )
					);
				} else {
					$arrCart = array('items' => 0, 'amount' => $this->view->Currency()->get(0));
				}
				print_r($arrCart);
				exit;
				echo json_encode(array('success' => true, 'cart' => $arrCart, 'credit' => array('amount' => '2,55 €') ));*/
				break;

			case "login":
				$this->forward("login","auth","user");
				break;
			case "registration":
				$this->forward("registration","auth","user");
				break;
			case "verifymail":
				$this->forward("verify","auth","user");
				break;
			case "forgotpw":
				$this->forward("forgotpassword","auth","user");
				break;
			case "contact":
				$this->forward("index","index","contact");
				break;


			case "ajrerror":
				exit;
				break;
			/* Ajax Autoload */

			case "removefromcart":
				$this->forward("removeitemfromcart","cart","billing");
				break;
			default:
				// AJR action ist nicht bekannt
				$response = Array('error' => true);
				echo json_encode($response);
				exit();
		}
	}
}
