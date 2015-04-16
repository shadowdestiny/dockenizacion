<?

class Zend_View_Helper_Cart
{
	public $view;

	public function Cart()
	{
		return $this;
	}

	public function getCartPriceTotal()
	{
		$obj_lc = new Logic_Cart();
		if($obj_lc->hasCartId())
		{
			$cart_id = $obj_lc->getCartId();
			return $obj_lc->getTotalPrice($cart_id);
			
		}
		else
		{
			return 0;
		}
	}

	public function getLink()
	{
				$obj_lc = new Logic_Cart();
		if($obj_lc->hasCartId())
		{
			$cart_id = $obj_lc->getCartId();
			 
			 $this->view->count = $obj_lc->getPostCount($cart_id);
			$this->view->price = $obj_lc->getTotalPrice($cart_id);;
			
		}
		else
		{
			$this->view->count = 0;//$sum;
			$this->view->price = '0.00 €';
		}
		
		return $this->view->render('billing/cart/cartlink.phtml');
	}


	public function setView(Zend_View_Interface $view)
	{
		$this->view = $view;
	}
}
