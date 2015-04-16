<?

class Default_Model_Currencies extends Zend_Db_Table_Abstract
{
	protected $_name = 'currencies';
	protected $_primary="id";

	public function getCurrencyList()
	{
		$cache = Zend_Registry::get("Zend_Cache");
		$cache_id = "Currency_List";

		if($cache->test($cache_id))
		{
			$list = $cache->load($cache_id);
		}
		else
		{
			$select = $this->select();
			$select->where("active=?",1);
			$select->where("default_locale<>?","");
			$select->order("currency");
			$data = $this->fetchAll($select);

			$list = Array();
			if($data->count()>0)
			{
				foreach($data as $item)
				{

					$list [ $item->currency ] = Array(
					"currency"=>$item->currency,
					"default_locale" => $item->default_locale,
					"format_pattern"=>$item->format_pattern,
					"rate"=>$item->rate
					);
				}

				$cache->save($list,$cache_id,Array(),3600);
			}
		}
		return $list;

	}
}