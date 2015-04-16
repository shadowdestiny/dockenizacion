<?

class Zend_View_Helper_Play_Tickets
{
	public $view;

	public function Play_Tickets()
	{
		return $this;
	}

	public function getMultiplePriceList()
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
	
	public function setView(Zend_View_Interface $view)
	{
		$this->view = $view;
	}
}
