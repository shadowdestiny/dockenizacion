<?php
//http://euromillions.com/login
//User Aldo
//PAssword: euromillions190

class Lottery_Admin_Euromillions_ResultsController extends Zend_Controller_Action
{

    public function init()
    {
		if(!hasAccess("acp") || !hasAccess("acp_lottery"))
		{
			$this->redirect($this->view->Url(Array(),"NoAccess",true));
		}
    }

	public function indexAction()
	{
		$obj_d=new Lottery_Model_Euromillions_Draws();
		$select=$obj_d->select();
		$select->order("draw_id desc");
		$list = $obj_d->fetchAll($select);

		$obj_r=new Lottery_Model_Euromillions_Results();
		$obj_w=new Lottery_Model_Euromillions_Winners();

		$last_draw_id = $list[0]->draw_id;

		$winners = $obj_w->fetchAll("draw_id=".$last_draw_id);
		$results = $obj_r->fetchAll("draw_id=".$last_draw_id);
		if($winners->count()>0 && $results->count()>0)
		{
			$this->view->canPublish=true;
		} else {
			$this->view->canPublish=false;
		}

		$this->view->list = $list;
	}

	public function editAction()
	{
		if(!hasAccess("acp_lottery_edit")){
			$this->redirect($this->view->Url(Array(),"NoAccess",true));
		}

		$obj_d=new Lottery_Model_Euromillions_Draws();
		$obj_r=new Lottery_Model_Euromillions_Results();
		$obj_w=new Lottery_Model_Euromillions_Winners();

		$request = $this->getRequest();

		$draw_id=$request->getParam("id");

		if($draw_id>0)
		{

			if($request->isPost())
			{
				$data = $request->getpost();

				if(isset($data['jackpot']))
				{
					$obj_d->update(Array("big_winner"=>$data['jackpotwin'],"jackpot"=>$data['jackpot']),"draw_id=".$draw_id);
				}

				if(isset($data['standard_1']))
				{
					$obj_r->delete("draw_id=".$draw_id);
					for($i=1;$i<=5;$i++)
					{
						$obj_r->insert(Array(
						"draw_id"=>$draw_id,
						"type"=>"standard",
						"pos"=>$i,
						"number"=>$data['standard_'.$i]
						));
					}

					for($i=1;$i<=2;$i++)
					{
						$obj_r->insert(Array(
						"draw_id"=>$draw_id,
						"type"=>"luckystar",
						"pos"=>$i,
						"number"=>$data['luckystar_'.$i]
						));
					}
				}

				if(isset($data['prize']))
				{
					$obj_w->delete("draw_id=".$draw_id);

					for($i=5;$i>=1;$i--)
					{
						for($j=2;$j>=0;$j--)
						{
							if($i >= 2 || ($i == 1 && $j >= 2))
							{
								$arrData=Array(
								"draw_id"=>$draw_id,
								"numbers"=>$i,
								"luckystars"=>$j,
								"prize"=>$data['prize'][$i][$j],
								"winners"=>$data['winner'][$i][$j]
								);

								$obj_w->insert($arrData);
							}
						}
					}

					/*
					foreach($data['prize'] as $id=>$p)
					{
						if(isset($data['winners']))
						{
							$arrData=Array(
							"prize"=>$data['prize'][$id],
							"winners"=>$data['winners'][$id]
							);
							$obj_w->update($arrData,"id=".$id);

						}
					}
					*/
				}

				$this->redirect($this->view->url(Array("module"=>"lottery","controller"=>"admin","action"=>"index")));
			}



			$select=$obj_d->select();
			$select->where("draw_id=?",$draw_id);
			$draw = $obj_d->fetchRow($select);


			for($i=1;$i<=5;$i++)
			{
				$arrResults['standard_'.$i]=0;
			}
			for($i=1;$i<=2;$i++)
			{
				$arrResults['luckystar_'.$i]=0;
			}
			$results = $obj_r->fetchAll("draw_id=".$draw_id);
			if($results->count()>0)
			{
				foreach($results as $r)
				{
					if($r->type=="standard")
					{
						$arrResults['standard_'.$r->pos]=$r->number;
					} else {
						$arrResults['luckystar_'.$r->pos]=$r->number;
					}
				}
			}





			for($i=5;$i>=1;$i--)
			{
				for($j=2;$j>=0;$j--)
				{
					$arrWinners[$i][$j]['prize']=0;
					$arrWinners[$i][$j]['winners']=0;
				}
			}
			$winners = $obj_w->fetchAll("draw_id=".$draw_id,"prize desc");
			if($winners->count()>0)
			{
				foreach($winners as $w)
				{
					$arrWinners[$w->numbers][$w->luckystars] ['prize']=$w->prize;
					$arrWinners[$w->numbers][$w->luckystars] ['winners']=$w->winners;

				}
			}

			//print_r($draw->toArray());
			//print_r($results->toArray());
			//print_r($winners->toArray());
			//exit;

			$data['draw']=$draw->toArray();
			$data['results']=$arrResults;
			$data['winners']=$arrWinners;
			$this->view->drawid = $draw_id;
			$this->view->data=$data;

		}
	}

	public function getdrawresultsajrAction()
	{
		$request = $this->getRequest();
		$draw_id=$request->getParam("id",0);
		if($draw_id>0)
		{
			$arrRet=Array(
			"jackpotwin"=>1,
			"jackpot"=>123456,
			"standard_1"=>1,
			"standard_2"=>2,
			"standard_3"=>3,
			"standard_4"=>4,
			"standard_5"=>5,
			"luckystar_1"=>1,
			"luckystar_2"=>2,
			);

			$winners=Array(
			5=>Array(0=>1,1=>2,2=>3),
			4=>Array(0=>2,1=>4,2=>6),
			3=>Array(0=>11,1=>22,2=>33),
			2=>Array(0=>111,1=>222,2=>333),
			1=>Array(2=>3333),
			);
			$prizes=Array(
			5=>Array(0=>3,1=>2,2=>1),
			4=>Array(0=>33,1=>22,2=>11),
			3=>Array(0=>333,1=>222,2=>111),
			2=>Array(0=>33333,1=>2222,2=>4444),
			1=>Array(2=>44444),
			);

			$arr=Array(
			"success"=>true,
			"data"=>$arrRet,
			"winners"=>$winners,
			"prizes"=>$prizes
			);
			echo Zend_Json::encode($arr);
		} else {
			echo Zend_Json::encode(Array("success"=>false,"error"=>true));
		}

		exit();
	}

	public function getdrawresultsajr2Action()
	{
		$request = $this->getRequest();
		$draw_id=$request->getParam("id",0);
		if($draw_id>0)
		{
			$obj_w = new Lottery_Model_Euromillions_Webservice();


			$arrRet=Array(
			"jackpotwin"=>1,
			"jackpot"=>123456,
			"standard_1"=>1,
			"standard_2"=>2,
			"standard_3"=>3,
			"standard_4"=>4,
			"standard_5"=>5,
			"luckystar_1"=>1,
			"luckystar_2"=>2,
			);

			$winners=Array(

			);
			$prizes=Array(

			);

			$arr=Array(
			"success"=>true,
			"data"=>$arrRet,
			"winners"=>$winners,
			"prizes"=>$prizes
			);
			echo Zend_Json::encode($arr);
		} else {
			echo Zend_Json::encode(Array("success"=>false,"error"=>true));
		}

		exit();
	}

	public function activatedrawAction()
    {

		$this->_helper->layout->disableLayout();

		$request = $this->getRequest();
		$draw_id = $request->getParam("lastdrawid",false);

		$this->view->draw_id=$draw_id;
		$this->view->nextDrawDate = $this->getDrawDateForDrawID($draw_id+1);
		$this->view->nextDrawId = ($draw_id+1);

		if($draw_id>0)
		{

			$obj_d=new Lottery_Model_Euromillions_Draws();
			$obj_r=new Lottery_Model_Euromillions_Results();
			$obj_w=new Lottery_Model_Euromillions_Winners();


			$winners = $obj_w->fetchAll("draw_id=".$draw_id);
			$results = $obj_r->fetchAll("draw_id=".$draw_id);

			if($winners->count()>0 && $results->count()>0)
			{
//print_r($draw_id);exit;
				if($request->isPost())
				{

					$post = $request->getPost();

					$data = Array(
					"published"=>1
					);
					$obj_d->update($data,"draw_id=".$post['drawid']);

					$data = Array(
					"draw_id"=>$post['next_draw_id'],
					"draw_date"=>$post['next_draw_date'],
					"published"=>0,
					"jackpot"=>$post['jackpot']
					);
					$obj_d->insert($data);

					$this->redirect($this->view->url(Array("module"=>"lottery","controller"=>"admin","action"=>"index")));

				}
			} else {
				$this->view->error=true;
			}
		} else {
					print_r($_POST);
			exit;
			$this->view->error=true;
		}
    }



	public function importAction()
	{

//POST /ResultsService.asmx/GetProjectedJackpot HTTP/1.1
//Host: resultsservice.lottery.ie

		mt_srand((double)microtime()*1000000);
	    $seq = mt_rand(1,100);

		$obj_w = new Lottery_Model_Webservice();
		$x= $obj_w->getResultForDate("2014-12-19");
		echo $x;
		exit;
		//$xml = $obj_w->getJackpot();

		//$xml;
		//exit;

		$xml = $obj_w->getResults2(3);
echo $xml;
exit;
//$a=$obj_w->getCompetitionAnswers(2);
//echo $a;
//exit;

		$obj_ld=new Lottery_Model_Euromillions_Draws();
		$obj_lr=new Lottery_Model_Euromillions_Results();
		$obj_lw=new Lottery_Model_Euromillions_Winners();

		//$i = 627;
		$i = 756;

		foreach($xml as $k1=>$data)
		{
			//print_r($data);
			//echo $data->NextDrawDate;
			//exit;


			//echo $data->DrawDate;
			//echo "<br>";
			//exit;

			$date = new Zend_Date($data->DrawDate);

			//$data = $obj_w->getResultForDate($v1->DrawDate);
			if($data)
			{
				$arrData = Array(
				"draw_id"=>$i,
				"jackpot" => (string)$data->DrawResult->TopPrize,
				"message" => (string) $data->DrawResult->Message ,
				"draw_date" => $date->toString("Y-M-d"),
				"big_winner" => (string)$data->DrawResult->Structure->Tier->Winners
				);
				//print_r($arrData);
				//exit;

				try
				{
					$obj_ld->insert($arrData);
				} catch(EXCEPTION $e)
				{
			//echo $e;
			//exit;
				}

				$standard=0;
				$luckystar=0;

				foreach($data->Numbers->DrawNumber as $number)
				{
					if($number->Type=="Standard")
					{
						$standard++;
						$arrData = Array(
						"draw_id"=>$i,
						"type"=>"standard",
						"pos"=>$standard,
						"number"=>(string) $number->Number
						);

					} elseif($number->Type=="LuckyStar")  {
						$luckystar++;
						$arrData = Array(
						"draw_id"=>$i,
						"type"=>"luckystar",
						"pos"=>$luckystar,
						"number"=>(string) $number->Number
						);
					}

					try
					{
						$obj_lr->insert($arrData);
					} catch(EXCEPTION $e)
					{
					}
				}



				// winners
				foreach($data->Structure->Tier as $winner)
				{
					//print_r($winner);

					$type = (string) $winner->Match;
					if(strlen($type)==3)
					{
						// with licky stars
						$arrTypes = explode("+",$type);
						$numbers = $arrTypes[0];
						$luckystars = $arrTypes[1];
					} elseif(strlen($type) == 1)
					{
						// normal number
						$numbers=$type;
						$luckystars=0;
					}
					//echo $type;
					//exit;

					$arrData = Array(
					"draw_id"=>$i,
					"winners"=>(string) $winner->Winners,
					"prize"=>(string) $winner->Prize,
					"numbers"=>$numbers,
					"luckystars"=>$luckystars,
					);
					//print_r($arrData);
//					exit("A");
					try
					{
						$obj_lw->insert($arrData);
					} catch(EXCEPTION $e)
					{
					}

				}
				$i--;
			}
			else
			{
				print_r($data);
				exit("no data");
			}
		}
		exit("done");
		//echo count($xml);
		//exit;

		$i=0;

		foreach($xml as $k1=>$v1)
		{
			$i++;

			//if( $i<594)
			{
			//	continue;
			}
			echo $k1." - ".$v1;
			echo "<br>";

			if(is_object($v1))
			{
				foreach($v1 as $k2=>$v2)
				{

					echo " - ".$k2." - ".$v2;
					echo "<br>";

					foreach($v2 as $k3=>$v3)
					{
						echo " - - ".$k3." - ".$v3;
						echo "<br>";

						foreach($v3 as $k4=>$v4)
						{
							echo "--------".$k4." - ".$v4;
							echo "<br>";
						}
					}

				}
			}
		}
		exit;
		exit;
		$c= new Zend_Soap_Client("resultsservice.lottery.ie/ResultsService.asmx");
		$c->GetProjectedJackpot();

	exit;

	$APIKEY = "751fbf6ddfb7c3857d898c21bfdc2b22";
$results = file("http://api.bentasker.co.uk/lottopredict/?action=retrieve&month=9&key=$APIKEY&game=15");
//$results = file("http://api.bentasker.co.uk/lottopredict/?action=LD&no=5&key=$APIKEY&game=1");
$results= file("http://api.bentasker.co.uk/lottopredict/?action=LatestResults&key=751fbf6ddfb7c3857d898c21bfdc2b22&game=3&draws=Any");
//$results= file("http://api.bentasker.co.uk/lottopredict/?key=751fbf6ddfb7c3857d898c21bfdc2b22&action=GameList");

print_r($results);


	exit;
		$obj_lr=new Lottery_Model_Euromillions_Results();
		$obj_lw=new Lottery_Model_Euromillions_Winners();

		/*
		$fh = fopen("data/winners.txt","r");
		while ($l = fgets($fh,4096))
		{
			$l = str_replace('INSERT dbo.Winnings (id, [result], condition, amount, winners) VALUES (','',$l);
			$l = rtrim($l,")\r\n");

			$arrRow = explode(", ",$l);
			$arrRow[2] = str_replace ("N'","",$arrRow[2]);
			$arrRow[2] = str_replace ("'","",$arrRow[2]);

			$arrRow[3] = str_replace ("N'","",$arrRow[3]);
			$arrRow[3] = str_replace ("'","",$arrRow[3]);

			$arrRow[4] = str_replace ("N'","",$arrRow[4]);
			$arrRow[4] = str_replace ("'","",$arrRow[4]);


			$arrData = Array(
			"result_id"=> $arrRow[1],
			"condition"=> $arrRow[2],
			"amount"=> $arrRow[3],
			"winners"=> $arrRow[4],
			);

			$obj_lw->insert($arrData);
		}
		fclose($fh);
		*/

		$fh = fopen("data/results.txt","r");

		while ($l = fgets($fh,4096))
		{
			$l = str_replace('INSERT dbo.Results (id, draw, numbers, stars, gameId, currency, jackpot) VALUES (','',$l);
			$l = rtrim($l,")\r\n");

			$arrRow = explode(", ",$l);

			$arrRow[1] = trim ($arrRow[1],"'");

			$arrRow[2] = str_replace ("N'","",$arrRow[2]);
			$arrRow[2] = str_replace ("'","",$arrRow[2]);
			$arrRow[3] = str_replace ("N'","",$arrRow[3]);
			$arrRow[3] = str_replace ("'","",$arrRow[3]);
			$arrRow[5] = str_replace ("N'","",$arrRow[5]);
			$arrRow[5] = str_replace ("'","",$arrRow[5]);
			$arrRow[6] = str_replace ("N'","",$arrRow[6]);
			$arrRow[6] = str_replace ("'","",$arrRow[6]);
			$arrRow[6] = str_replace (".","",$arrRow[6]);

			$year = substr(  $arrRow[1],0,4);
			$month = substr(  $arrRow[1],4,2);
			$day = substr(  $arrRow[1],6,2);

			$date = new Zend_Date($year."-".$month."-".$day);


			$arrData = Array(
			"id"=>$arrRow[0],
			"draw"=> $date->toString("Y-M-d"),
			"numbers"=> $arrRow[2],
			"stars"=> $arrRow[3],
			"game_id"=> $arrRow[4],
			"currency"=> $arrRow[5],
			"jackpot"=> $arrRow[6],
			);
			//print_r($arrData);
			//exit;
			$obj_lr->insert($arrData);
		}
		fclose($fh);
		exit;
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

	public function calcDrawDates()
	{
		$obj_d=new Lottery_Model_Euromillions_Draws();

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

}