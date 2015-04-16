<?

class Billing_Model_Cart extends Zend_Db_Table_Abstract
{
	protected $_name = "carts";
	protected $_primary="cart_id";
	
	
	//mfg
	
	public function old_addItemToCart($type="ticket",$name="",$price="0.00",$data)
	{
	
		
		if(Zend_Auth::getInstance()->hasIdentity())
		{
		
			$identity = Zend_Auth::getInstance()->getIdentity();
			
			$select = $this->obj_cart_user->select();
			$select->where("user_id=?",$identity->user_id);
						
			$cart = $this->obj_cart_user->fetchRow($select);
			
			$date = new Zend_Date();
			
			if(!$cart)
			{
				// create new cart for user
				
				
				
				$arrData  = Array(
				"user_id"=>$identity->user_id,
				"last_action"=>$date->toString('yyyy-MM-dd HH:mm:ss'),
				"creation_date"=>$date->toString('yyyy-MM-dd HH:mm:ss'),
				"state"=>"open"
				);
				$cart_id = $this->obj_cart_user->insert($arrData);
			} else {
				$cart_id = $cart->cart_id;
			}
			
			if($cart_id>0)
			{
				$arrItemData = Array(
				"cart_id"=>$cart_id,
				"type"=>$type,
				"data"=>$data,
				"name"=>$name,
				"price"=>$price,
				"add_date"=>$date->toString('yyyy-MM-dd HH:mm:ss'),
				"tuesday"=>$data['tuesday'],
				"friday"=>$data['friday'],
				"num_draws"=>$data['num_draws'],
				"price"=>$price
				);
				
				try
				{
				
					if ( $this->obj_cart_user_item->insert( $arrItemData ) )
					{
						return true;
					} else {
						return false;
					}
				} catch(EXCEPTION $e)
				{
					echo $e;
					exit;
					return false;
				}
				
			}
			
			return $cart_id;
		}		
	}
	
		
	
}