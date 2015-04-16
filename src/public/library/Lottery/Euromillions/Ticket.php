<?php

class Lottery_Euromillions_Ticket
{
	var $numbers = Array();
	var $stars = Array();
	
	var $isValid = false;
	
	var $tuesday=0;
	var $friday=0;
	var $num_draws=0;
	var $recurring = 0;
	var $startDrawDate=0;
			
	public function __construct()
	{
		
	}
	
	public function addNumbers($arrNumbers)	
	{
		if($arrNumbers)
		{
			if(is_array($arrNumbers))
			{			
				foreach($arrNumbers as $number)
				{
					if($this->validateNumber($number,"number"))
					{
						$this->numbers[] = $number;
					} else {
						$this->resetTicket();
						return false;
					}										
				}
				asort($this->numbers);
				
				$this->isValid = $this->validateTicket($this->numbers,$this->stars);
				
								return true;
			}
			else
			{
				if(is_numeric($arrNumbers))
				{
					if($this->validateNumber($arrNumbers,"number"))
					{					
						$this->numbers[] = $arrNumbers;
						asort($this->numbers);
							
						$this->isValid = $this->validateTicket($this->numbers,$this->stars);
							
						return true;

					} else {
						$this->resetTicket();
						return false;
					}					
				}
			}
		}
		else
		{
			$this->resetTicket();
			return false;
		}
	}
	
	public function addStars($arrStars)	
	{
	
		if($arrStars)
		{		
			if(is_array($arrStars))
			{
				foreach($arrStars as $star)
				{
					if($this->validateNumber($star,"star"))
					{
						if(!in_array($star,$this->stars))
						{
							$this->stars[] = $star;
							asort($this->stars);							
						} else {						
							$this->resetTicket();
							return false;
						}						
					} else {
						$this->resetTicket();
						return false;
					}					
				}
				
				$this->isValid = $this->validateTicket($this->numbers,$this->stars);
				return true;
			}
			else
			{
				if(is_numeric($arrStars))
				{				
					if($this->validateNumber($arrStars,"star"))
					{					
						$this->stars[] = $arrStars;
						asort($this->stars);
						return true;
					} else {
						$this->resetTicket();
						return false;
					}					
				}
			}
		}
		else
		{
			$this->resetTicket();
			return false;
		}
	}
	
	public function getTicketPrice()
	{		
		$this->isValid = $this->validateTicket();
		
		if ( $this->isValid)
		{						
			$nums = count($this->numbers);
			$stars = count($this->stars);
			
			$obj_lep = new Lottery_Euromillions_Price();
			
			$amount =0;
			if($this->tuesday)
			{
				$amount = ($amount + $obj_lep->getTicketPrice($nums,$stars) );
			}
			if($this->friday)
			{
				$amount = ($amount + $obj_lep->getTicketPrice($nums,$stars) );
			}
			
			if($this->num_draws>1)
			{
				$amount = ($amount * $this->num_draws);
			}
			return $amount;
		}
		else
		{
			return 0;
		}
	}
	
	public function getTicketTypeId()
	{	
		if($this->isValid)
		{		
			$ticket_type = $this->getTicketTypeByNumbers($this->numbers,$this->stars);
			if($ticket_type)
			{
				return $ticket_type['ticket_type_id'];
			}
		}
		return 0;
	}
	
	
	public function getProductId()
	{	
		if($this->isValid)
		{		
			$ticket_type = $this->getTicketTypeByNumbers($this->numbers,$this->stars);
			if($ticket_type)
			{
				$obj_bp = new Billing_Model_Product();
				$select = $obj_bp->select();
				$select->where("product_type_id=?",$ticket_type['ticket_type_id']);
				$select->where("active=?",1);
				$select->where("category=?","euromillions");
				$data = $obj_bp->fetchRow($select);
				
				if($data)
				{
					return $data->product_id;;
				} else {
					return 0;
				}
			}
		}
		return 0;
	}
	
	
	
	public function validateNumber($number=0,$type="number")
	{		
		if(!is_numeric ( $number))
		{
			return false;
		}
		
		if($type=="number")
		{
			if($number>=1 && $number<=50 && count($this->numbers)<10)
			{
				if( !in_array($number,$this->numbers))
				{
					return true;
				} else {
					return false;
				}
			}
			else
			{
				return false;
			}
		} elseif($type=="star")
		{
			if($number>=1 && $number<=11 && count($this->stars)<5)
			{
				if(!in_array($number,$this->stars))
				{
					return true;
				} else {
					return false;
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
			
		return true;
	}
	
	
	public function validateTicketNumbers($numbers=Array(),$stars=Array())
	{			
		if(count($numbers)<5 or count($stars)<2)
		{				
			return false;
		}		
	
		foreach($numbers as $number)
		{
			if( !is_numeric($number) || $number<1 || $number>50)
			{
				return false;
			}			
		}

		foreach($stars as $star)
		{
			if( !is_numeric($star) || $star<1 || $star>11)
			{
				return false;
			}			
		}		
		$this->isValid=true;
		return true;
	}
	
	
	public function validateTicket()
	{					
		if($this->validateTicketNumbers($this->numbers,$this->stars))
		{
			if($this->tuesday or $this->friday)
			{
				return true;
			}
			else
			{
				return false;
			}
		} else {
			return false;
		}
	}
	
	function resetTicket()
	{
		unset( $this->numbers);
		unset( $this->stars);
		
		$this->numbers=Array();
		$this->stars=Array();
	}
	
	public function getTicketPriceList()
	{
		$cache = Zend_Registry::get("Zend_Cache");
				
		$obj_ptt = new Lottery_Model_Euromillions_Type();
		
		$cache_id="Play_Multiple_Price_List";
		
		if($cache->test($cache_id))
		{
			$arrPriceList = $cache->load($cache_id);
		}
		else
		{		
			$select = $obj_ptt->select();
			$select->where("active=?",1);		
			$data = $obj_ptt->fetchAll($select);
			
			foreach($data as $item)
			{
				$arrPriceList[$item->numbers] [$item->stars] = $item->price;
			}
			$cache->save($arrPriceList,$cache_id,Array(),3600);
		}
		return $arrPriceList;
	}
	
	public function setTuesday($value=1)
	{
		$this->tuesday = $value;
		$this->isValid = $this->validateTicket($this->numbers,$this->stars);
	}
	
	public function setFriday($value=1)
	{
		$this->friday = $value;
		$this->isValid = $this->validateTicket($this->numbers,$this->stars);
	}
	
	public function setNumDraws($value=1)
	{
		$this->num_draws = $value;
		$this->isValid = $this->validateTicket($this->numbers,$this->stars);		
	}
	
	public function setRecurring($value=0)
	{
		$this->recurring = $value;
		$this->isValid = $this->validateTicket($this->numbers,$this->stars);		
	}
	
	public function setStartDrawDate($value=0)
	{
		$this->startDrawDate = new Zend_Date($value);
		$this->isValid = $this->validateTicket($this->numbers,$this->stars);		
	}
	
	/*
	returns the Ticket Type Data for given set of numbers of a line
	*/
	public function getTicketTypeByNumbers($arrNumbers=Array(),$arrStars=Array())
	{
		$obj_tt = new Lottery_Model_Euromillions_Type();
		$select = $obj_tt->select();
		$select->where("numbers=?",count($arrNumbers));
		$select->where("stars=?",count($arrStars));
		$select->where("active=?",1);
		
		$data = $obj_tt->fetchRow($select);
		
		if ( $data )
		{
			return $data->toArray();		
		} else {
			return false;
		}		
	}	
	
	public function getNextPossibleDrawDate()
	{
	}
	
	public function getDrawDates()
	{
		if ( $this->isValid)
		{						
			$obj_led = new Lottery_Euromillions_Draw();
			$nextDrawDate = $obj_led->getNextDrawDate();			
			
			$nextDrawDay = $obj_led->getNextDrawDay();			
			$nextDrawDates = $obj_led->getNextDrawDates($this->num_draws);
						
			$retArr = Array();
			
			
			if($this->num_draws>1)
			{
				
			}
			
			echo $this->friday.$this->tuesday;
			if($this->tuesday && !$this->friday)
			{			
				// check if next possible day is tuesday
				if(current($nextDrawDates) ==2 )
				{
					$retArr[] = current($nextDrawDates);
				}
				else
				{
					alert("getNextPossibleDrawDates: Tuesday limit reached!");
				}
			}
			elseif(!$this->tuesday && $this->friday)
			{
				// check if next possible day is tuesday
				if(current($nextDrawDates) ==5 )
				{
					$retArr[] = current($nextDrawDates);
				}
				else
				{
					alert("getNextPossibleDrawDates: Friday limit reached!");
				}
			}
			return $retArr;
			exit;
			
			if($this->tuesday && $this->friday)
			{
				if($drawDay==2)
				{
					echo "begin ab die";
				} else {
					echo "begin ab fr";
				}
				echo "beiedes";
			}
			
			exit;
			echo $obj_led->isInTimeLimit();
			exit;
			
			if( $nextDrawDate )
			{
				$drawDate = new Zend_Date($nextDrawDate);
				$now = new Zend_Date();
				
				$drawDay = $drawDate->get(Zend_Date::WEEKDAY_DIGIT);
				$nowDay = $now->get(Zend_Date::WEEKDAY_DIGIT);
				
				$retDates = Array();
				
				if($this->tuesday)
				{
					while($now->get(Zend_Date::WEEKDAY_DIGIT)<>2)
					{
						$now->add(1,Zend_Date::DAY);
					}
					$retDates[] = $now->get(Zend_Date::DATETIME);
				}
				
				$now = new Zend_Date();
				
				if($this->friday)
				{
					while($now->get(Zend_Date::WEEKDAY_DIGIT)<>5)
					{
						$now->add(1,Zend_Date::DAY);
					}
					$retDates[] = $now->get(Zend_Date::DATETIME);
				}
			
				asort($retDates);
				return $retDates;
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
	}
	
	public function getRealTicketList()
	{
		
	}
}