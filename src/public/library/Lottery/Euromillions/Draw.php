<?php

class Lottery_Euromillions_Draw
{
	// defines the maximal time for transactions
	var $max_hour = 19;
	var $max_minute = 0;
	
	public function init()
	{
	
	}
	
	public function getNextDrawDate()
	{
		$obj_d = new Lottery_Model_Euromillions_Draws();
		$select = $obj_d->select();
		$select->order("draw_id desc");
		$select->where("published=0");
		
		$data = $obj_d->fetchRow($select);
		if($data)
		{
			$date = new Zend_Date($data->draw_date);
			$date->setHour($this->max_hour);
			$date->setMinute($this->max_minute);
			return $date->get(Zend_Date::DATETIME);			
		} else {
			return "";
		}
	}
	
	public function calcDrawDates()
	{
		$obj_d = new Lottery_Model_Euromillions_Draws();
		
		$select = $obj_d->select();				
		$select->where("draw_id=?",615);		
		$select->order("draw_id");
		$data = $obj_d->fetchRow($select);
		
		if($data)		
		{
			$arrDrawDates = Array();
			
			$locale = "de_DE";
			
			$end = new Zend_Date(null,$locale);
			$end->add(1,Zend_Date::YEAR);
			$end->setHour(0);
			$end->setMinute(0);
			$end->setSecond(0);
						
			$start = new Zend_Date($data->draw_date,$locale);
			
			$nextDrawId = $data->draw_id;
			
			$arrDrawDates [ $data->draw_date ] = $data->draw_id;
			
			while($start->isEarlier($end))
			{
				$nextDrawId++;
				
				$dow = date("N",$start->get(Zend_Date::TIMESTAMP));
				if($dow==2)
				{
					$start->add(3,Zend_Date::DAY);
				} elseif($dow==5) {
					$start->add(4,Zend_Date::DAY);
				} else {
					alert("Error by calculating next draw date");
				}
								
				$arrDrawDates[ $start->toString('yyyy-MM-dd') ] = $nextDrawId;
			}			
			return $arrDrawDates;
		} else {
			return Array();
		}		
	}
	
	public function getDrawIdForDrawDate($drawDate)
	{			
		if(Zend_Date::isDate($drawDate))
		{				
			$date = new Zend_Date($drawDate,"de_DE");
			
			$drawDates = $this->calcDrawDates();
			
			if(array_key_exists($date->toString('yyyy-MM-dd'),$drawDates))
			{
				return $drawDates [$date->toString('yyyy-MM-dd')];
			} else {
				alert("getDrawidfordrawdate: wrong date");
				return false;
			}
		}
		else 
		{
			return false;
		}
	}
	
	
	public function getDrawDateForDrawID($draw_id)
	{			
	
		if(is_numeric($draw_id))
		{				
			$drawDates = $this->calcDrawDates();
			
			$drawDates = array_flip($drawDates);
			
			if(array_key_exists($draw_id,$drawDates))
			{
				return $drawDates [ $draw_id ];
			} else {
				return false;
			}
		}
		else 
		{
			return false;
		}
	}
	
	public function getNextDrawID()
	{	
		$obj_d = new Lottery_Model_Euromillions_Draws();
		$select = $obj_d->select();
		$select->order("draw_id desc");
		$select->where("published=0");
		
		$data = $obj_d->fetchRow($select);
		if($data)
		{
			return $data->draw_id;
		} else {
			return "";
		}	
	}
	
	public function getNextDrawDay()
	{	
		$t = time();
		$dow = date("N",$t);			

		$hour = date("G",$t);	
		$minute = date("i",$t);	
		
		if( $dow==1 or ($dow==2 &&  $hour<$this->max_hour ) )
		{						
			// tuesday
			return 2;
		}
		elseif($dow<=4 or ( $dow==5 && $hour<$this->max_hour ) )
		{
			// friday
			return 5;
		} else {
			// tuesday
			return 2;
		}			
	}
	
	public function getNextTuesday()
	{
	
		$nextDrawDates = $this->getNextDrawDates(2);
		foreach($nextDrawDates as $date=>$dow)
		{
			if($dow == 2)
			{
				return $date;
			}
		}		
	}
	
	
	public function getNextFriday()
	{
	
		$nextDrawDates = $this->getNextDrawDates(2);
		foreach($nextDrawDates as $date=>$dow)
		{
			if($dow == 5)
			{
				return $date;
			}
		}		
	}
	
	public function isInTimeLimit()
	{
		$nextDrawDate = $this->getNextDrawDate();
		if($nextDrawDate)
		{
			$next = new Zend_Date($nextDrawDate);
			$next->setHour($this->max_hour);
			$next->setMinute($this->max_minute);
			
			$now = new Zend_Date();
			
			if( $now->isEarlier($next) )
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
	
	public function timeToNextDraw()
	{
		$nextDrawDate = $this->getNextDrawDate();
		if($nextDrawDate)
		{
			$next = new Zend_Date($nextDrawDate);
			$next->setHour($this->max_hour);
			$next->setMinute($this->max_minute);
			
			$now = new Zend_Date();
			
			if( $now->isEarlier($next) )
			{
				$diff = ( $next->get(Zend_Date::TIMESTAMP) - $now->get(Zend_Date::TIMESTAMP));
				
				return round( $diff / 60);
			} else {
				return 0;
			}
		}
		else 
		{
			return 0;
		}
	}
	
	public function getNextDrawDates($num=1)
	{
		$nextDrawDate = $this->getNextDrawDate();
		
		$t = new Zend_Date($nextDrawDate);
		$dow = date("N",$t->get(Zend_Date::TIMESTAMP));
		
		$arrDates  = Array();
		
		for ($i=1; $i<=$num;$i++)
		{		
		
			if($i==1)
			{			
				$arrDates [$nextDrawDate]=$dow;
			} else {
			
				if($dow==2)
				{				
					$t->add(3,Zend_Date::DAY);
					$dow = date("N",$t->get(Zend_Date::TIMESTAMP));
				} elseif($dow==5)
				{
					$t->add(4,Zend_Date::DAY);
					$dow = date("N",$t->get(Zend_Date::TIMESTAMP));
				}
				
				$arrDates [$t->toString()]=$dow;
				
			}
		}
		return $arrDates;

	}
}