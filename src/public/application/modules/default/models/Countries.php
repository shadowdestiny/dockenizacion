<?

class Default_Model_Countries extends Zend_Db_Table_Abstract
{
	protected $_name = 'countries';
	protected $_primary="country_id";

	public function getAllCountries($type="frontend")
	{
		$cache = Zend_Registry::get("Zend_Cache");
		$cache_id = "Currency_List_" . $type;

		if($cache->test($cache_id)){
			$arrCountries = $cache->load($cache_id);
		} else {
			$select = $this->select();
			if($type=="frontend"){
				$select->where("active_registration=?",1);
			}
			$select->order("short_code");
			$data = $this->fetchAll($select);
			foreach($data as $item)
			{
				$arrCountries [$item->short_code]=$item->name;
			}
			$cache->save($arrCountries,$cache_id,Array(),3600);
		}
		return $arrCountries;
	}

	public function getAllPayoutCountries()
	{
		$select = $this->select();
		$select->where("active_payout=?",1);
		$select->order("short_code");
		$data = $this->fetchAll($select);
		foreach($data as $item){
			$arrCountries [$item->short_code] = Array(
				'iban_mandatory' => $item->iban_mandatory,
				'iban_example' =>  $item->iban_example
			);
		}
		return $arrCountries;
	}

	public function importCountries()
	{
		$trans=Zend_Registry::get("Zend_Translate");


		$file=APPLICATION_PATH."/../_stuff/countries-20140629.csv";
		if(file_exists($file))
		{
			$csv=file_get_contents($file);
			$arrCsv = explode("\n",$csv);
			for($i=1;$i<=count($arrCsv);$i++)
			{
				$arrRow = explode(",",$arrCsv[$i]);
				if(count($arrRow)>2)
				{
					$country_code = $arrRow[0];
					if($country_code<>"")
					{
						$select = $this->select();
						$select->where("short_code=?",$country_code);
						$data = $this->fetchRow($select);
						if($data)
						{

						} else {
							$arrData = Array(
							"short_code"=>$arrRow[0],
							"name"=>$arrRow[1],
							"active_registration"=>0,
							"active_payout"=>0
							);
							$this->insert($arrData);
						}

					}
				}
				else
				{

				}
			}

			return true;
		} else {
			return false;
		}
	}
}