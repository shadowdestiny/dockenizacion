<?php

class Lottery_Euromillions_Price
{
	var $ticketPriceList = Array();
	
	public function __construct()
	{
		$this->ticketPriceList = $this->getTicketPriceList();
	}
		
	public function getTicketPrice($count_numbers=0,$count_stars=0)
	{
	
		if ( $count_numbers>0 && $count_stars>0)
		{			
			return $this->ticketPriceList[$count_numbers][$count_stars];
		}
		else
		{
			return 0;
		}
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
	
	
	public function validateTicket($numbers=Array(),$stars=Array())
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
		
		return true;
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
}