<?

class Default_Model_Stats extends Zend_Db_Table_Abstract
{
	protected $statsTypes;

	public function getStatsData($start,$end,$customer_id=0)
	{

		$obj_st = new Default_Model_Stats_Types();
		$obj_s = new Default_Model_Stats_Data();

		$arrStatusTypes = $this->getStatsTypes();
		$arrStatusTypes = array_flip($arrStatusTypes);


		$startDate = new Zend_Date($start,"de_DE");
		$endDate = new Zend_Date($end,"de_DE");


		$startDate->setLocale("de_DE");
		$startDate->setHour(0);
		$startDate->setMinute(1);
		$startDate->setSecond(0);

		$endDate->setLocale("de_DE");
		$endDate->setHour(0);
		$endDate->setMinute(0);
		$endDate->setSecond(0);

		$type = $this->getType($startDate->get(Zend_Date::TIMESTAMP),$endDate->get(Zend_Date::TIMESTAMP));

		$select = $obj_s->select();
		$select->where("STR_TO_DATE(CONCAT_WS('-', `year`, `month`, `day`), '%Y-%m-%d') between '".$startDate->toString("yyyy-MM-dd")."' and '".$endDate->toString("yyyy-MM-dd")."'");


		if($type=="hourly")
		{
			$select->order("hour");
			$data = $obj_s->fetchAll($select);

			for($h=0;$h<=23;$h++)
			{
				foreach($arrStatusTypes as $t=>$v)
				{
					$arrData['data'] [$h]  [$arrStatusTypes[$t]] = 0;

					$arrData['total'] [$arrStatusTypes[$t]] =0;
					$arrData['avg'] [$arrStatusTypes[$t]] =0;
					$arrData['min'] [$arrStatusTypes[$t]] =0;
					$arrData['max'] [$arrStatusTypes[$t]] =0;
				}

							}

			if($data->count()>0)
			{
				foreach($data as $item)
				{
					$arrData ['data'] [$item->hour]  [$arrStatusTypes[$item->stats_type_id]] = $item->value;

					$arrData ['total'] [$arrStatusTypes[$item->stats_type_id]]+= $item->value;
					$arrData ['avg'] [$arrStatusTypes[$item->stats_type_id]]+= $item->value;

					if ( $arrData ['min'] [$arrStatusTypes[$item->stats_type_id]]>=$item->value)
					{
						$arrData ['min'] [$arrStatusTypes[$item->stats_type_id]]+= $item->value;
					}
					if ( $arrData ['max'] [$arrStatusTypes[$item->stats_type_id]]<=$item->value)
					{
						$arrData ['max'] [$arrStatusTypes[$item->stats_type_id]]+= $item->value;
					}
				}
			}


			foreach($arrStatusTypes as $t=>$v)
			{
				$i=0;
				$sum=0;
				foreach($arrData['data']  as $hour => $items)
				{
					$i++;
					$sum+=$items[$v];
				}
				$arrData['avg'][$v]=round( ($sum/$i) ,2);
				//$arrData['avg'][$v]=$sum;;

			}
		}
		elseif($type=="daily")
		{

			$select->from( Array("stats_data"), Array("*","c"=>"sum(value)") );

			$select->order("day");
			$select->group("day");
			$select->group("stats_type_id");

			$data = $obj_s->fetchAll($select);

			$endDate->add(1,Zend_Date::DAY);
			while($startDate->isEarlier($endDate))
			{

				foreach($arrStatusTypes as $t=>$v)
				{
					$arrData ['data'] [ $startDate->toString("yyyy-MM-dd")]  [$arrStatusTypes[$t]] = 0;

					$arrData['total'] [$arrStatusTypes[$t]] =0;
					$arrData['avg'] [$arrStatusTypes[$t]] =0;
					$arrData['min'] [$arrStatusTypes[$t]] =0;
					$arrData['max'] [$arrStatusTypes[$t]] =0;
				}

				$startDate->add(1,Zend_Date::DAY);
			}


			if($data->count()>0)
			{
				foreach($data as $item)
				{
					$d = $item->year."-".$item->month."-".$item->day;
					$date = new Zend_Date($d);
					$arrData ['data'] [$date->toString("yyyy-MM-dd")]  [$arrStatusTypes[$item->stats_type_id]] = $item->c;

					$arrData ['total'] [$arrStatusTypes[$item->stats_type_id]]+= $item->c;
					$arrData ['avg'] [$arrStatusTypes[$item->stats_type_id]]+= $item->c;

					if ( $arrData ['min'] [$arrStatusTypes[$item->stats_type_id]]>=$item->c)
					{
						$arrData ['min'] [$arrStatusTypes[$item->stats_type_id]]+= $item->c;
					}
					if ( $arrData ['max'] [$arrStatusTypes[$item->stats_type_id]]<=$item->c)
					{
						$arrData ['max'] [$arrStatusTypes[$item->stats_type_id]]+= $item->c;
					}
				}
			}


			foreach($arrStatusTypes as $t=>$v)
			{
				$i=0;
				$sum=0;
				foreach($arrData['data']  as $date => $items)
				{
					$i++;
					$sum+=$items[$v];
				}
				$arrData['avg'][$v]=round( ($sum/$i) ,2);
			}
		}
		elseif($type=="monthly")
		{

			$select->from( Array("stats_data"), Array("*","c"=>"sum(value)") );

			$select->order("year");
			$select->group("month");
			$select->group("stats_type_id");
			//echo $select;
			//exit;
			$data = $obj_s->fetchAll($select);

			while($startDate->isEarlier($endDate))
			{
				$arrData [ $startDate->toString("yyyy-MM")] =0;
				foreach($arrStatusTypes as $t=>$v)
				{
					//$arrData [ $startDate->toString("yyyy-MM")]  [$arrStatusTypes[$t]] = 0;
				}

				$startDate->add(1,Zend_Date::DAY);
			}


			if($data->count()>0)
			{
				foreach($data as $item)
				{
					//$d = $item->year."-".$item->month."-".$item->day;
					//$arrData [$d]  [$arrStatusTypes[$item->stats_type_id]] = $item->c;
				}
			}

		}


		return $arrData;
	}

	public function set($type="",$customer_id=0)
	{
		if($customer_id==0)
		{
			$customer_id = 1;
		}

		if($type<>"")
		{
			if(!is_array($this->statsTypes))
			{
				$this->statsTypes = $this->getStatsTypes();
			}

			if(!array_key_exists($type,$this->statsTypes))
			{
				$statsType = $this->addStatsType($type);
			} else {
				$statsType = $this->statsTypes[$type];
			}

			if($statsType>0)
			{
				$date = new Zend_Date();
				//$date->setMinute(0);
				//$date->setSecond(0);

				//return true;
//return true;
				$obj_sd = new Default_Model_Stats_Data();
				try
				{

					$arrData = Array(
					"year"=>$date->get(Zend_Date::YEAR),
					"month"=>$date->get(Zend_Date::MONTH),
					"day"=>$date->get(Zend_Date::DAY),
					"hour"=>$date->get(Zend_Date::HOUR),
					"customer_id"=>$customer_id,
					"stats_type_id"=>$statsType,
					"value"=>1
					);

					$obj_sd->insert($arrData);
					return true;
				} catch(EXCEPTION $e)
				{
					$select = $obj_sd->select();
					$select->where("year=?",$date->get(Zend_Date::YEAR));
					$select->where("month=?",$date->get(Zend_Date::MONTH));
					$select->where("day=?",$date->get(Zend_Date::DAY));
					$select->where("hour=?",$date->get(Zend_Date::HOUR));
					$select->where("stats_type_id=?",$statsType);
					$select->where("customer_id=?",$customer_id);

					$data = $obj_sd->fetchRow($select);
					if($data)
					{
						$data->value=($data->value+1);
						$obj_sd->update($data->toArray(),"`year`=".$date->get(Zend_Date::YEAR)." and `month`=".$date->get(Zend_Date::MONTH)." and `day`=".$date->get(Zend_Date::DAY)." and  `hour`=".$date->get(Zend_Date::HOUR)." and stats_type_id=".$statsType." and customer_id=".$customer_id);
						return true;
					} else {
						alert("Stats: set: error: no entry found");
						return false;
					}
				}
			} else {
				alert("Stats set: StatsTypeID not set");
			}

		} else {
			return false;
		}

	}

	public function setTicketStats($type="",$value=1,$draw_id=0,$product_id=0,$customer_id=0)
	{
		if($customer_id==0)
		{
			$customer_id = 1;
		}

		if(
			$type<>""
			&& $value<>""
			&& $draw_id<>""
			&& $product_id<>""
		)
		{
			try
			{
				$obj_stl = new Default_Model_Stats_Ticket_Lc();
				$select = $obj_stl->select();
				$select->where("draw_id=?",$draw_id);
				$select->where("customer_id=?",$customer_id);
				$select->where("product_id=?",$product_id);

				$data = $obj_stl->fetchRow($select);
				if($data)
				{

					$data = $data->toArray();
					if( array_key_exists($type, $data))
					{
						$data[$type] = ($data[$type] + $value);

						$obj_stl->update($data,"draw_id=".$draw_id);
						return true;
					} else {
						return false;
					}
				} else {
					$arrData=Array(
					"draw_id"=>$draw_id,
					"customer_id"=>$customer_id,
					"product_id"=>$product_id,
					$type=>$value,
					);
					$obj_stl->insert($arrData);
					return true;
				}
			} catch(EXCEPTION $e)
			{
				return false;
			}
		} else {
			return false;
		}

	}


	public function getStatsTypes()
	{
		$arrTypes = Array();

		$cache = Zend_Registry::get("Zend_Cache");
		$cache_id = "Stats_Types";

		if($cache->test($cache_id))
		{
			$arrTypes = $cache->load($cache_id);
		} else {



			$obj_st = new Default_Model_Stats_Types();
			$types = $obj_st->fetchAll();
			if($types->count()>0)
			{
				foreach($types as $type)
				{
					$arrTypes [$type->type]=$type->stats_type_id;
				}
			}
			$cache->save($arrTypes,$cache_id,Array($cache_id),60);
			$this->statsTypes = $arrTypes;
		}
		return $arrTypes;
	}

	public function addStatsType($type)
	{
		$cache = Zend_Registry::get("Zend_Cache");
		$cache_id = "Stats_Types";

		$cache->remove($cache_id);

		$obj_st = new Default_Model_Stats_Types();
		$arrData = Array(
			"type"=>$type
		);
		try
		{
			return $obj_st->insert($arrData);
		} catch(EXCEPTION $e)
		{
			$select = $obj_st->select();
			$select->where("type=?",$type);
			$data = $obj_st->fetchRow($select);
			if($data)
			{
				return $data['stats_type_id'];
			}
			return 0;
		}
	}

	public function getType($start,$end)
	{


		$startDate = new Zend_Date($start,"de_DE");
		$endDate = new Zend_Date($end);

		$days=0;


		while ($endDate->isLater($startDate))
		{
			$days++;
			$startDate->add(1,Zend_Date::DAY);
		}


		if($days<=1)
		{
			return "hourly";
		}
		elseif($days<=31)
		{
			return "daily";
		} else {
			return "monthly";
		}
	}
}