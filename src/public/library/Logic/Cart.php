<?php

class Logic_Cart
{
	
	public function init()
	{

	}
	
	public function getCartId()
	{
		if(Zend_Auth::getInstance()->hasIdentity())
		{
			$identity = Zend_Auth::getInstance()->getIdentity();
			$user_id = $identity->user_id;
			
			$obj_bc = new Billing_Model_Cart();
			$select = $obj_bc->select();
			$select->where("user_id=?",$user_id);
			$select->where("state<>?","done");
			$cartData = $obj_bc->fetchRow($select);
			
			if($cartData)
			{
				return $cartData->cart_id;
			} else {
				$arrData = Array(
				"user_id"=>$user_id,
				"creation_date"=>Zend_Date::now()->toString('yyyyMMddHHmmss'),
				"last_update"=>Zend_Date::now()->toString('yyyyMMddHHmmss'),
				"state"=>"open"				
				);
				try
				{
					$cart_id = $obj_bc->insert($arrData);
					return $cart_id;
				} catch(EXCEPTION $e)
				{
					alert($e);
					exit;
				}
			}			
		} else {
			if(Settings::get("C_guest_id"))
			{				
				$guest_id = Settings::get("C_guest_id");
			} else {			
				$guest_id = md5(time().rand(1,55555555));
			}
			
			$obj_bc = new Billing_Model_Cart();
			$select = $obj_bc->select();
			$select->where("guest_id=?",$guest_id);
			$select->where("state<>?","done");
			$cartData = $obj_bc->fetchRow($select);
			
			if($cartData)
			{
				return $cartData->cart_id;
			} else {
				$arrData = Array(
				"guest_id"=>$guest_id,
				"creation_date"=>Zend_Date::now()->toString('yyyyMMddHHmmss'),
				"last_update"=>Zend_Date::now()->toString('yyyyMMddHHmmss'),
				"state"=>"open",
				);
				try
				{
					$cart_id = $obj_bc->insert($arrData);
					Settings::set("C_guest_id",$guest_id);
					return $cart_id;
				} catch(EXCEPTION $e)
				{
					alert($e);
					exit;
				}
			}
		}
	}
	
	public function getCartStatus($cart_id)
	{
		$obj_bc = new Billing_Model_Cart();
		$select = $obj_bc->select();
		$select->where("cart_id=?",$cart_id);		
		$cartData = $obj_bc->fetchRow($select);
		
		if($cartData)
		{
			return $cartData->state;
		} else {
			return "";
		}
	}
	
	public function hasCartId()
	{
		if(Zend_Auth::getInstance()->hasIdentity())
		{
			$identity = Zend_Auth::getInstance()->getIdentity();
			$user_id = $identity->user_id;
			
			$obj_bc = new Billing_Model_Cart();
			$select = $obj_bc->select();
			$select->where("user_id=?",$user_id);
			$select->where("state<>?","done");
			$cartData = $obj_bc->fetchRow($select);
			
			if($cartData)
			{
				return true;
			} else {
				return false;
			}			
		} else {
		
			if(Settings::get("C_guest_id"))
			{				
				$guest_id = Settings::get("C_guest_id");
				
				$obj_bc = new Billing_Model_Cart();
				$select = $obj_bc->select();
				$select->where("guest_id=?",$guest_id);
				$select->where("state<>?","done");
				$cartData = $obj_bc->fetchRow($select);
				
				if($cartData)
				{
					return true;
				}
			}
			return false;
		}
	}

	public function addItemToCart($cart_id,$post_id,$lottery,$ticketData)
	{		
	
	
		if($cart_id>0 && $post_id>0 && $lottery<>"")
		{
			if($lottery=="euromillions")
			{			
				$obj_emd = new Lottery_Euromillions_Draw();
				$obj_cd = new Billing_Model_Cart_Data();
				
				$startDrawDate = new Zend_Date($ticketData->startDrawDate,"de_DE");
				
				$select = $obj_cd->select();
				$select->where("cart_id=?",$cart_id);
				$select->where("post_id=?",$post_id);
				$select->where("lottery=?",$lottery);
				$cartData = $obj_cd->fetchRow($select);
				
				if($cartData)
				{				
					$cart_data_id= $cartData->cart_data_id;
					$totalPrice = $cartData->price_total;
				}
				else
				{					
					$arrData = Array(
					"cart_id"=>$cart_id,
					"post_id"=>$post_id,
					"lottery"=>$lottery,
					"add_date"=>Zend_Date::now()->toString('yyyyMMddHHmmss'),
					"price_total"=>0,
					"start_draw_date"=>$startDrawDate->toString('yyyyMMddHHmmss')
					);
					
			
					$cart_data_id = $obj_cd->insert($arrData);
					$totalPrice=0;
				}
				
				if($cart_data_id>0)
				{
					
					$obj_cdl = new Billing_Model_Cart_Data_Lc();

					
					$arrLcData = Array(
					"cart_data_id"=>$cart_data_id,
					"abo"=>$ticketData->recurring,
					"num_draws"=>$ticketData->num_draws,
					"tuesday"=>$ticketData->tuesday,
					"friday"=>$ticketData->friday,
					"numbers"=>implode(",",$ticketData->numbers),
					"stars"=>implode(",",$ticketData->stars),
					"add_date"=>Zend_Date::now()->toString('yyyyMMddHHmmss'),
					"draw_date"=>$startDrawDate->toString('yyyyMMddHHmmss'),
					"price"=>$ticketData->getTicketPrice(),
					"ticket_type_id"=>$ticketData->getTicketTypeId(),
					);					

					$totalPrice = ($totalPrice+$arrLcData['price']);
					
					try
					{
						$obj_cdl->insert($arrLcData);
						
						$obj_cd->update(Array("price_total"=>$totalPrice),"cart_data_id=".$cart_data_id);
						
						$drawDate = new Zend_Date( $startDrawDate );
						$draw_id = $obj_emd->getDrawIdForDrawDate ($drawDate);						
						
						$product_id = $ticketData->getProductID();
												
						$obj_s = new Default_Model_Stats();
						$obj_s->setTicketstats("add_to_cart",$ticketData->num_draws,$draw_id,$product_id);
						
						return true;
					} catch(EXCEPTION $e)
					{
						echo $e;
						return false;
					}					
				}
				else
				{
					return false;
				}								
			}
			
		}
		else
		{
			alert("AddItemToCart ERROR: Wrong Data given: ".$cart_id.",".$post_id.",".$lottery.",".serialize($data));
			return false;
		}
	}
	
	public function updateCart($cart_id)
	{
		if($cart_id>0)
		{

			$obj_c = new Billing_Model_Cart();
			$obj_cd = new Billing_Model_Cart_Data();
			
			$select = $obj_cd->select();
			$select->from("cart_data",Array("c"=>"sum(price_total)"));
			$select->where("cart_id=?",$cart_id);
			$data = $obj_cd->fetchRow($select);
			
			if($data)
			{
				$total_price = $data->c;
			} else {
				$total_price=0;
			}
			
			$arrData = Array(
			"total_price"=>$total_price,
			"last_update"=>Zend_Date::now()->toString('yyyyMMddHHmmss'),
			);
			try
			{
				$obj_c->update($arrData,"cart_id=".$cart_id);
				return true;
			} catch(EXCEPTION $e) {
				alert("Fehler beim UpdateCart");
				return false;
			}
			
		}
	}
		
			
	/*
	returns the sum of a cart for a user without currency
	*/
	public function getTotalPrice($cart_id)
	{
		if($cart_id>0)
		{
			$obj_c = new Billing_Model_Cart();
			$select = $obj_c->select();
			$select->where("cart_id=?",$cart_id);
			$cartData = $obj_c->fetchRow($select);
			if($cartData)
			{
				return $cartData->total_price;
			} else {
				return 0;
			}		
		}
	}
	
	
/*
	returns the sum of a cart elements
*/	
	public function getPostCount($cart_id)
	{
		if($cart_id>0)
		{
			$obj_c = new Billing_Model_Cart();
			$obj_cd = new Billing_Model_Cart_Data();
			
			$select = $obj_c->select();
			$select->where("cart_id=?",$cart_id);
			$cart = $obj_c->fetchRow($select);
			
			if($cart)
			{		
				$select = $obj_cd->select();							
				$select->from("cart_data",Array("c"=>"count(*)"));
				$select->where("cart_id=?",$cart->cart_id);
				$item = $obj_cd->fetchRow($select);
				if($item)
				{
					return $item->c;
				} else {
					return 0;
				}						
			} else {
				return "0";
			}					
		}
		else
		{
			return 0;
		}
	}
	
	
	public function getItems($cart_id)
	{
		$obj_c = new Billing_Model_Cart();
		$obj_cd = new Billing_Model_Cart_Data();
		
		$select = $obj_c->select();
		$select->where("cart_id=?",$cart_id);
		$cart = $obj_c->fetchRow($select);
		
		if($cart)
		{
			$select = $obj_cd->select();							
			$select->where("cart_id=?",$cart->cart_id);
			$items = $obj_cd->fetchAll($select);
			
			if($items)
			{								
				$arrTicketList  = Array();
				$arrList = Array();
				
				
				foreach($items as $item)
				{													
					$arrGroup=Array();
					$arrGroup['price_total']=0;
				
					$arrGroup['cart_data_id']=$item->cart_data_id;
					$arrGroup['price_total']=($arrGroup['price_total']+$item['price_total']);
					$arrGroup['lottery']=$item['lottery'];
					$arrGroup['post_id']=$item['post_id'];
					$arrGroup['start_draw_date']=$item['start_draw_date'];
					$arrGroup['count_lines']=0;
					$arrGroup['tickets']=Array();
										
					if($item->lottery=="euromillions")
					{															
						$arrTicketList=Array();

						$obj_bcl=new Billing_Model_Cart_Data_Lc();
						
						$emTickets = $obj_bcl->fetchAll("cart_data_id=".$item->cart_data_id);
						
						if($emTickets->count()>0)
						{							
							foreach($emTickets as $emTicket)
							{
								$arrTicket = Array();
								
								$add_date = new Zend_Date($emTicket->add_date);
								$now = new Zend_Date();
								$now->sub(7,Zend_DAte::DAY);							
								$now->setHour(19);
								$now->setMinute(0);
								$now->setSecond(0);

								
								
								$arrTicket['id'] = $emTicket['id'];																
								$arrTicket['numbers']=explode(",",$emTicket['numbers']);
								$arrTicket['stars']=explode(",",$emTicket['stars']);
								
								$arrTicket['tuesday'] = $emTicket['tuesday'];
								$arrTicket['friday'] = $emTicket['friday'];
								$arrTicket['abo'] = $emTicket['abo'];							
								$arrTicket['price'] = $emTicket['price'];							
								
								
								$obj_let = new Lottery_Euromillions_Ticket();
								$ticketType = $obj_let->getTicketTypeByNumbers($arrTicket['numbers'],$arrTicket['stars']);

								if($ticketType)
								{
									$arrTicket['title']=$ticketType['name'];
									
									if($ticketType['ticket_type_id']>1)
									{
										$arrTicket['ticket_type']="multi";									
									} else {
										$arrTicket['ticket_type']="single";
									}
									
									$arrGroup['count_lines'] = ($arrGroup['count_lines']+1);
								} else {
									$$arrTicket['title']="Euromillions-Ticket";
								}
								
								$arrTicket['num_draws_total'] =$emTicket['num_draws'];

								$arrTicketList[]=$arrTicket;													
							}							
						}												
						$arrGroup['tickets'] = $arrTicketList;					
					}
					
					$arrList[] = $arrGroup;
				}

				return $arrList;
				return $arrTicketList;
			} else {
				return "0";
			}						
		} else {
			return Array();
		}					
	}
	
	
	public function cleanupCart($cart_id)
	{
		debug("running Cleanup Cart");
		$obj_lottery_em_draws = new Lottery_Euromillions_Draw();
		$next_draw_date = $obj_lottery_em_draws->getNextDrawDate();
	
		$obj_c = new Billing_Model_Cart();
		$obj_cd = new Billing_Model_Cart_Data();
		
		$select = $obj_c->select();
		$select->where("cart_id=?",$cart_id);
		$cart = $obj_c->fetchRow($select);
		
		if($cart)
		{
			$select = $obj_cd->select();							
			$select->where("cart_id=?",$cart->cart_id);
			$items = $obj_cd->fetchAll($select);
			
			$countCartData = $items->count();
			$countCartDataItems=0;
			$deleteCart=false;
			
			if($items->count()>0)
			{												
				foreach($items as $item)
				{
					if($item->lottery=="euromillions")
					{															

						$obj_bcl=new Billing_Model_Cart_Data_Lc();
						
						$emTickets = $obj_bcl->fetchAll("cart_data_id=".$item->cart_data_id);
						
						$countEmTickets=$emTickets->count();
						
						if($emTickets->count()>0)
						{													
							$countDelete=0;
							foreach($emTickets as $emTicket)
							{
								
								$ticket_draw_date = new Zend_Date($emTicket->draw_date);
								
								$nextDrawDate = new Zend_Date( $next_draw_date );

								$nextDrawDate->setHour(19);
								$nextDrawDate->setMinute(0);
								$nextDrawDate->setSecond(0);

								if(  $ticket_draw_date->isEarlier($nextDrawDate))
								{
									info("delete EM Ticket from Cart: to old");
									// old cart item will be delete
									$obj_bcl->delete("id=".$emTicket->id);
								}
								
							}
							
							// recheck
							$obj_bcl=new Billing_Model_Cart_Data_Lc();
							$emTickets = $obj_bcl->fetchAll("cart_data_id=".$item->cart_data_id);						
							$countEmTickets=$emTickets->count();
						}						
						
						if($countEmTickets == 0)
						{
							info("Delete Cart Data from Cart: to old");
							$obj_cd->delete("cart_data_id=".$item->cart_data_id);														
						}
					}
				}								
				
				// recheck
				$select = $obj_cd->select();							
				$select->where("cart_id=?",$cart->cart_id);
				$items = $obj_cd->fetchAll($select);

				if($items->count()==0)
				{
					$deleteCart=true;
				}
			}
			else
			{
				$deleteCart=true;
			}
			
			if($deleteCart)
			{
				info("Delete expired cart ".$cart_id);
				$obj_c->delete("cart_id=".$cart_id);
			}
		}		
	}
	
	
	public function delItemGroupFromCart($cart_id,$post_id)
	{
		if($cart_id>0 && $post_id>0)
		{
			$obj_c = new Billing_Model_Cart();
			$obj_cd = new Billing_Model_Cart_Data();
			
			$select = $obj_c->select();
			$select->where("cart_id=?",$cart_id);
			$cart = $obj_c->fetchRow($select);
			
			if($cart)
			{				

				$select = $obj_cd->select();							
				$select->where("cart_id=?",$cart->cart_id);
				$select->where("post_id=?",$post_id);				
				$items = $obj_cd->fetchAll($select);
								
				if($items->count()>0)
				{																	
				
					foreach($items as $item)
					{																								
						if($item->lottery=="euromillions")
						{
							$obj_bcl = new Billing_Model_Cart_Data_Lc();
							$select = $obj_bcl->select();
							$select->where("cart_data_id=?",$item->cart_data_id);
							$arrCartDataLc = $obj_bcl->fetchAll($select);
							if($arrCartDataLc->count()>0)
							{
								$obj_emd = new Lottery_Euromillions_Draw();
								$obj_s = new Default_Model_Stats();

								foreach($arrCartDataLc as $LcItem)
								{
					
									$drawDate = new Zend_Date( $LcItem->draw_date );
									$draw_id = $obj_emd->getDrawIdForDrawDate ($drawDate);						
																		
									$ticket_type_id = $LcItem->ticket_type_id;
									
									$obj_bcl->delete("id=".$LcItem->id);

									$obj_s = new Default_Model_Stats();
									$obj_s->setTicketstats("remove_from_cart",1,$draw_id,$product_id);
								}
							}							
						}
						$obj_cd->delete("cart_data_id=".$item->cart_data_id." and post_id=".$post_id);
					}													
					$this->updateCart($cart_id);
					return true;
				} else {
					return false;
				}						
			} else {
				return false;
			}
		}
		else
		{
			return false;
		}
	}
	
	
	// get items of a cart grouped by lottery and post id
	public function getCartItems($cart_id)
	{
		$obj_cdl = new Billing_Model_Cart_Data_Lc();
		
		$ret['total_price'] = 0;
		$ret['total_items'] =0;
		$ret['items']=Array();

		if( $cart_id > 0)
		{
			
			$ret['total_price'] = $this->getTotalPrice($cart_id);

			$ret['total_posts'] = $this->getPostCount($cart_id);

			if($ret['total_posts']>0)
			{							
				$items = $this->getItems($cart_id);
				

				foreach($items as $item)
				{				
					
					$arrItems[$item['lottery']][ $item['post_id'] ] ['post_id']=$item['post_id'];
					$arrItems[$item['lottery']][ $item['post_id'] ] ['cart_data_id']=$item['cart_data_id'];
					$arrItems[$item['lottery']][ $item['post_id'] ] ['start_date']=$item['start_draw_date'];
					$arrItems[$item['lottery']][ $item['post_id'] ] ['price_total']=$item['price_total'];
					
					$arrItems[$item['lottery']][ $item['post_id'] ] ['tickets'] = $item['tickets'];
					
					$arrItems[$item['lottery']][ $item['post_id'] ] ['count_lines_total']=0;
					$arrItems[$item['lottery']][ $item['post_id'] ] ['count_draws_total']=0;
					
					$i=1;
					foreach($item['tickets'] as $ticket)
					{						
						$arrItems[$item['lottery']][ $item['post_id'] ] ['count_lines_total']++;
						
						if($i==1)
						{
							$arrItems[$item['lottery']][ $item['post_id'] ] ['count_draws_total']+=$ticket['num_draws_total'];
						}
						$i++;
					}
										
				}
				$ret['items']=$arrItems;
				
				return $ret;
				

				foreach( $arrItems as $lottery=>$posts)
				{				
					foreach($posts as $post_id=>$data)
					{					
					
						if($lottery=="euromillions")
						{						
							if(count($data['tickets']>0))
							{
							
							
								$obj_emt = new Lottery_Euromillions_Ticket();
								$obj_emt->addNumbers($data['tickets'][0]['numbers']);
								$obj_emt->addStars($data['tickets'][0]['stars']);
								$obj_emt->tuesday = $data['tickets'][0]['tuesday'];
								$obj_emt->friday = $data['tickets'][0]['friday'];
								$obj_emt->num_draws = $data['tickets'][0]['num_draws_total'];
								$obj_emt->startDrawDate = $data['tickets'][0]['start_date'];

								if($obj_emt->validateTicket())
								{								
								
//									$arrDate = $obj_emt->getDrawDateS();
									$obj_emd = new Lottery_Euromillions_Draw();
									$nextDrawDay = $obj_emd->getNextDrawDay();
									
									$nextTuesday = $obj_emd->getNextTuesday();
									$nextFriday = $obj_emd->getNextFriday();
									
									if ($data['tickets'][0] ['tuesday'] && $data['tickets'][0] ['friday'])
									{
										if($nextDrawDay==2)
										{
											$date = $nextFriday;
										} else {
											$date = $nextFriday;
										}
									}
									elseif ($data['tickets'][0] ['tuesday'] )
									{
										$date = $nextTuesday;
									}
									elseif ( $data['tickets'][0] ['friday'])
									{
										$date = $nextFriday;
									}
									
								}
								else
								{
									$date ="";
								}								
							}
							else
							{
								$date="";
							}
							
							$arrItems[$lottery] [ $post_id ] ['start_date'] = $date;
														
						}

					}
				}

				$ret['items'] = $arrItems;
			} else {
				$ret['items'] = Array();
			}
		}

		return $ret;
	}
	
	public function updateCartData($cart_id)
	{
		$cart_id=4;
		
		if($cart_id>0)
		{
			$obj_cd = new Billing_Model_Cart_Data();

			$cartData = $this->getCartItems($cart_id);

			foreach($cartData['items'] as $lottery=>$lotteryData)
			{
				if($lottery=="euromillions")
				{
					
					foreach($lotteryData as $post_id=>$postData)
					{												
					
						$obj_cd->update(Array(
						"start_draw_date"=>$postData['start_date'],
						),"cart_id=".$cart_id." and post_id=".$post_id);
					}
				}
			}
		}
		exit("D");
	}
}