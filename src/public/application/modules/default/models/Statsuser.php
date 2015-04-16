<?

class Default_Model_Statsuser extends Zend_Db_Table_Abstract
{

	public function getStatsData($start,$end,$country='',$customer_id=0)
	{

		$obj_s = new Default_Model_Stats_Useronline();

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
//echo $country;dexit;
		if($country <> ''){
			$select->where("country LIKE ?",$country);
		}


		if($type=="day"){
			if($country <> ''){
//$select->group("country");
			}

		/**	$select->group("year");
			$select->group("month");
			$select->group("day");
**/
		//	echo $select;exit;

			$select->from( Array("stats_user_online"));
			if($country == ''){
				$select->order("country");
			}
			//echo $select;
			$data = $obj_s->fetchAll($select);

		} elseif($type=="daily"){

			$select->from( Array("stats_user_online"), Array("*","gender_m"=>"sum(gender_m)","gender_f"=>"sum(gender_f)","gender_n"=>"sum(gender_n)") );

			$select->order(array("year","month","day"));
			$select->group(array("year","month","day"));
			$data = $obj_s->fetchAll($select);

		}
		return $data;

		if($type=="daily")
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
			return "day";
		} elseif($days > 1 && $days < 31) {
			return "daily";
		}

	}

}