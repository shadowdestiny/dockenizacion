<?php

class Logic_Order
{
	
	public function init()
	{

	}
	
	/*
	first step after payment process
	convert a cart to a order without looking for
	*/
	
	public function changeCartToOrder($cart_id=0,$transaction_id=0)
	{

		if($cart_id>0)
		{
			$obj_c = new Billing_Model_Cart();
			$obj_cd = new Billing_Model_Cart_Data();
			$obj_cdl = new Billing_Model_Cart_Data_Lc();

			$obj_o = new Billing_Model_Order();
			$obj_od = new Billing_Model_Order_Data();
			$obj_odl = new Billing_Model_Order_Data_Lc();
			
			$cart = $obj_c->fetchRow("cart_id=".$cart_id." and user_id>0 and state='open'");
			if($cart)
			{
				// update cart item to lock
				$obj_c->update(Array("state"=>"in_progress"),"cart_id=".$cart->cart_id);
				
				$select = $obj_cd->select();				
				$select->where("cart_id=?",$cart->cart_id);
				$cartData = $obj_cd->fetchAll($select);
				
				if($cartData->count()>0)
				{
					// create order
					
					$arrOrder = Array(
					"user_id"=>$cart->user_id,
					"customer_id"=>$cart->customer_id,
					"state"=>"new",
					"order_date"=>Zend_Date::now()->toString('yyyyMMddHHmmss'),
					"total_price"=>$cart->total_price,
					"transaction_id"=>$transaction_id
					);
					$order_id = $obj_o->insert($arrOrder);

					if($order_id>0)
					{
					
						foreach($cartData as $item)
						{
							// order data into db
							$arrOrderData = Array(
							"order_id"=>$order_id,							
							"lottery"=>$item->lottery,
							"post_id"=>$item->post_id,
							"add_date"=>Zend_Date::now()->toString('yyyyMMddHHmmss'),
							"product_id"=>$item->product_id,
							"price"=>$item->price,
							"state"=>"new"
							);
							$order_data_id = $obj_od->insert($arrOrderData);
							
							if($order_data_id>0)
							{
								if($item->lottery == "euromillions")
								{
									$select = $obj_cdl->select();
									$select->where("cart_data_id=?",$item->cart_data_id);

									$lcData = $obj_cdl->fetchRow($select);
									if($lcData)
									{
										$arrLcData = Array(
										"order_id"=>$order_id,
										"order_data_id"=>$order_data_id,
										"add_date"=>Zend_Date::now()->toString('yyyyMMddHHmmss'),
										"abo"=>$lcData->abo,
										"tuesday"=>$lcData->tuesday,
										"friday"=>$lcData->friday,
										"num_draws"=>$lcData->num_draws,
										"numbers"=>$lcData->numbers,
										"stars"=>$lcData->stars,
										"state"=>"new"
										);
										$lc_id = $obj_odl->insert($arrLcData);
										if($lc_id>0)
										{
											// update cart and mark as done
											$obj_c->update(Array("state"=>"done"),"cart_id=".$cart_id);
											
											return $order_id;
										} else {
											alert("error by storing of lc data for order_data_id=".$order_data_id);
											return false;
										}
									} else {
										
										alert("no lc data found for order_data_id=".$order_data_id);
										return false;
									}									
								}							
							} else {
								alert("error by storing order data for order_id=".$order_id);
								return false;
							}
						}
					}
					else
					{
						
						return false;
					}					
				}
				else
				{
					return false;
				}
				
				return $order_id;
			}
			else
			{
				return false;
			}
			
			return "AA";
		}		
	}
	
	public function processOrder($order_id=0)
	{
		
		$debug= true;
		
		if($order_id>0)
		{		
			$obj_o = new Billing_Model_Order();
			$obj_od = new Billing_Model_Order_Data();
			$obj_odl = new Billing_Model_Order_Data_Lc();

			$select = $obj_o->select();
			$select->where("order_id=?",$order_id);
			$select->where("user_id>0");
			$select->where("state=?","new");

			$order = $obj_o->fetchRow($select);
			
			if($order)
			{			
				if(!$debug)
				{
					$obj_o->update(Array("state"=>"in_progress"),"order_id=".$order_id);
				}
				
				$select = $obj_od->select();				
				$select->where("order_id=?",$order->order_id);
				$orderData = $obj_od->fetchAll($select);
				
				if($orderData->count()>0)
				{

					$error = false;
				
					foreach($orderData as $item)
					{
						$order_data_error=false;
						
						if(!$debug)
						{
							$obj_od->update(Array("state"=>"in_progress"),"order_data_id=".$item->order_data_id);
						}
						
						// euromillions
						if($item->lottery == "euromillions")
						{

							$select = $obj_odl->select();
							$select->where("order_data_id=?",$item->order_data_id);

							$lcData = $obj_odl->fetchRow($select);
							if($lcData)
							{
								$order_data_lc_error=false;
								
								if(!$debug)
								{
									$obj_odl->update(Array("state"=>"in_progress"),"id=".$lcData->id);
								}
								
								print_r($lcData->toArray());
								exit;
								
								$obj_emt = new Lottery_Euromillions_Ticket();
								
								$obj_emt->resetTicket();
								$obj_emt->addNumbers(explode(",",$lcData->numbers));
								$obj_emt->addStars( explode(",",$lcData->stars) );
								$obj_emt->setTuesday ( $lcData->tuesday);
								$obj_emt->setFriday( $lcData->friday);
								$obj_emt->setNumDraws( $lcData->num_draws);
								$obj_emt->setRecurring( $lcData->abo );

								if($obj_emt->validateTicket())
								{
									$obj_llt = new Lottery_Model_Lc_Ticket();
									
									
									// check if time limit reached									
									$obj_emd = new Lottery_Euromillions_Draw();
									
									if( $obj_emd->timeToNextDraw() >=15)
									{
									
									print_r($order->toArray());
									exit;
										$obj_et = new Lottery_Model_Euromillions_Ticket();
										
										print_r($obj_emt->getDrawDates());
										exit;
										
										$date = new Zend_Date ($obj_emd->getNextDrawDate());
										
										$arrEmTicket = Array(
 										"order_id"=>$order->order_id,
	 									"customer_id"=>$order->customer_id,
										"user_id"=>$order->user_id,
										"numbers"=>implode(",",$obj_emt->numbers),
										"stars"=>implode(",",$obj_emt->stars),
										"tuesday"=>$obj_emt->tuesday,
										"friday"=>$obj_emt->friday,
										"status"=>"new",
										"added_on"=>Zend_Date::now()->toString('yyyyMMddHHmmss'),
										"start_draw_id"=>$obj_emd->getNextDrawId(),
										"start_date"=>$date->toString('yyyyMMddHHmmss'),
										"end_draw_id"=>$obj_emd->getNextDrawId(),
										"end_date"=>$date->toString('yyyyMMddHHmmss'),
										);
										print_r($arrEmTicket);
										exit;
										$em_ticket_id = $obj_et->insert($arrEmTicket);
										echo $em_ticket_id;
										
										
									}
									else
									{
										$msg="error by validating em ticket ".$lcData['id'];
										$error = true;
										$order_data_error=true;
										$order_data_lc_error=true;
									}

								}
								else
								{
									$error=true;
									$order_data_error=true;
									$order_data_lc_error=true;
								}
								
								
							}
							else
							{
								$msg = "no lc data found for the order ".$order->order_id." and data id ".$item->order_data_id;
								$error=true;
								$order_data_error=true;
							}
							
							if($order_data_lc_error)
							{
								if(!$debug)
								{
									$obj_odl->update(Array("state"=>"error"),"id=".$lcData->id);
								}
							} else {
								
							}								
						}
						
						// other cases
						
						if($order_data_error)
						{
							if(!$debug)
							{
								$obj_od->update(Array("state"=>"error"),"order_data_id=".$item->order_data_id);
							}
						}
						else
						{
							if(!$debug)
							{
								$obj_od->update(Array("state"=>"done"),"order_data_id=".$item->order_data_id);
							}
						}
					}
				} else {
					$error=true;
				}
				
				if($error)
				{
					if(!$debug)
					{
						$obj_o->update(Array("state"=>"error"),"order_id=".$order_id);
					}
					return false;
				}
				if(!$debug)
				{
					$obj_o->update(Array("state"=>"done"),"order_id=".$order_id);
				}
				return true;
				
			}
			else
			{
				return false;
			}
		} else  {
			return false;
		}
		
	}
}