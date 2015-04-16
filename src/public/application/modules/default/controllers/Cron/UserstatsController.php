<?php

class Cron_UserstatsController extends Zend_Controller_Action
{

    public function init()
    {

    }

	/*
		sends mails from the mail queue to real mail accounts
	*/
	public function processAction()
	{
		$time = new Zend_Date();
		$hour = $time->get(Zend_Date::HOUR_SHORT);
		$minute = $time->get(Zend_Date::MINUTE_SHORT);

		$time->sub('01:00:00',Zend_Date::TIMES);
		$start_time = $time->toString('yyyy-MM-dd HH:00:00');
		$end_time = $time->toString('yyyy-MM-dd HH:59:59');

		$obj_user = new User_Model_User();
		$objStats = new Default_Model_Stats_Data();
		$debug = true;

		if($minute == 0 || $debug){
			/** Update User Online Statistic **/
			$select = $obj_user->select();
			$select->where("last_action between '" . $start_time . "' AND '" . $end_time . "'");
			$arrUsers = $obj_user->fetchAll($select)->toArray();

			if(!empty($arrUsers)){
				$userOnline = count($arrUsers);
				$statsTypeOnline = 37;
				try
				{
					$arrData = Array(
						"year"=>$time->get(Zend_Date::YEAR),
						"month"=>$time->get(Zend_Date::MONTH),
						"day"=>$time->get(Zend_Date::DAY),
						"hour"=>$time->get(Zend_Date::HOUR),
						"customer_id"=>1,
						"stats_type_id"=>$statsTypeOnline,
						"value"=>$userOnline
					);

					$objStats->insert($arrData);
				} catch(EXCEPTION $e)
				{
					$select = $objStats->select();
					$select->where("year=?",$time->get(Zend_Date::YEAR));
					$select->where("month=?",$time->get(Zend_Date::MONTH));
					$select->where("day=?",$time->get(Zend_Date::DAY));
					$select->where("hour=?",$time->get(Zend_Date::HOUR));
					$select->where("stats_type_id=?",$statsTypeOnline);
					$select->where("customer_id=?",1);

					$data = $objStats->fetchRow($select);
					if($data)
					{
						$data->value=($data->value+$userOnline);
						$objStats->update($data->toArray(),"`year`=".$time->get(Zend_Date::YEAR)." and `month`=".$time->get(Zend_Date::MONTH)." and `day`=".$time->get(Zend_Date::DAY)." and  `hour`=".$time->get(Zend_Date::HOUR)." and stats_type_id=". $statsTypeOnline ." and customer_id=1");
					} else {
						alert("Stats: set: error: no entry found");
					}
				}
			}

			/** Update User Login Statistic **/
			$select = $obj_user->select();
			$select->where("last_login between '" . $start_time . "' AND '" . $end_time . "'");
			$arrLogins = $obj_user->fetchAll($select)->toArray();

			if(!empty($arrLogins)){
				$userLogins = count($arrLogins);
				$statsTypeLogin = 38;
				try
				{
					$arrData = Array(
						"year"=>$time->get(Zend_Date::YEAR),
						"month"=>$time->get(Zend_Date::MONTH),
						"day"=>$time->get(Zend_Date::DAY),
						"hour"=>$time->get(Zend_Date::HOUR),
						"customer_id"=>1,
						"stats_type_id"=>$statsTypeLogin,
						"value"=>$userLogins
					);

					$objStats->insert($arrData);
				} catch(EXCEPTION $e)
				{
					$select = $objStats->select();
					$select->where("year=?",$time->get(Zend_Date::YEAR));
					$select->where("month=?",$time->get(Zend_Date::MONTH));
					$select->where("day=?",$time->get(Zend_Date::DAY));
					$select->where("hour=?",$time->get(Zend_Date::HOUR));
					$select->where("stats_type_id=?",$statsTypeLogin);
					$select->where("customer_id=?",1);

					$data = $objStats->fetchRow($select);
					if($data)
					{
						$data->value=($data->value+$userLogins);
						$objStats->update($data->toArray(),"`year`=".$time->get(Zend_Date::YEAR)." and `month`=".$time->get(Zend_Date::MONTH)." and `day`=".$time->get(Zend_Date::DAY)." and  `hour`=".$time->get(Zend_Date::HOUR)." and stats_type_id=". $statsTypeLogin ." and customer_id=1");
					} else {
						alert("Stats: set: error: no entry found");
					}
				}
			}
		}

		if(($hour == 0 && $minute == 0 && !empty($arrUsers)) || $debug){

			$arrInsert = Array();
			$select2 = $obj_user->getAdapter()->select();
			$select2->from(Array("u"=>"users"),Array("last_login" => "u.last_login","user_id" => "u.user_id","c" => "COUNT(ud.country)"))
					->join(Array("ud" => "user_details"), "u.user_id = ud.user_id",array('country','gender'))
					->where("u.last_action between '" . $start_time . "' AND '" . $end_time . "'")
					->group("ud.country")
					->group("ud.gender");

			$arrUserGenderCountry = $obj_user->getAdapter()->fetchAll($select2);

			foreach($arrUserGenderCountry as $status){
				$arrayKey = $time->get(Zend_Date::YEAR) . '' . $time->get(Zend_Date::MONTH). '' . $time->get(Zend_Date::DAY) . '' . $status['country'];
				$arrInsert[$arrayKey]['year'] = $time->get(Zend_Date::YEAR);
				$arrInsert[$arrayKey]['month'] = $time->get(Zend_Date::MONTH);
				$arrInsert[$arrayKey]['day'] = $time->get(Zend_Date::DAY);
				$arrInsert[$arrayKey]['country'] = $status['country'];
				$arrInsert[$arrayKey]['gender_' . $status['gender']] = $status['c'];
			}

			$obj_uo = new Default_Model_Stats_Useronline();
			foreach($arrInsert as $insert){

				try	{
					$obj_uo->insert($insert);		print_r($insert);exit;
				} catch(EXCEPTION $e) {
					$select = $obj_uo->select();
					$select->where("year=?",$time->get(Zend_Date::YEAR));
					$select->where("month=?",$time->get(Zend_Date::MONTH));
					$select->where("day=?",$time->get(Zend_Date::DAY));
					$select->where("country=?",$insert['country']);

					$data = $obj_uo->fetchRow($select)->toArray();

					if($data){
						if(!empty($insert['gender_m'])){ $data['gender_m']=($data['gender_m']+$insert['gender_m']); }
						if(!empty($insert['gender_f'])){$data['gender_f']=($data['gender_f']+$insert['gender_f']); }
						if(!empty($insert['gender_n'])){$data['gender_n']=($data['gender_n']+$insert['gender_n']); }
						$obj_uo->update($data,"`year`=".$time->get(Zend_Date::YEAR)." and `month`=".$time->get(Zend_Date::MONTH)." and `day`=".$time->get(Zend_Date::DAY)." and `country`='". $insert['country'] ."'");
					} else {
						alert("Stats: set: error: no entry found");
					}
				}
			}
		}

		/* Cleanup Session Table */
		$obj_us = new User_Model_Session();
		$tstamp = time() - 60;

		//$where = $obj_us->getAdapter()->quoteInto();
		$obj_us->delete(array("last_action < ?" => $tstamp));
		exit;
	}
}