<?php

class Lottery_CronController extends Zend_Controller_Action
{

    public function init()
    {

    }

	public function importAction()
	{

		$obj_w = new Lottery_Model_Euromillions_Webservice();
		
		//$xml = $obj_w->getJackpot();
		//echo $xml;
		//exit;

		$xml = $obj_w->getResults2(1);

//$a=$obj_w->getCompetitionAnswers(2);
//echo $a;
//exit;
		
		$obj_ld = new Lottery_Model_Euromillions_Draws();
		$obj_lw = new Lottery_Model_Euromillions_Winners();
		$obj_lr = new Lottery_Model_Euromillions_Results();
		
		$select = $obj_ld->select();
		$select->order("draw_id desc");
		$select->limit(2);
		
		$draws = $obj_ld->fetchAll($select);
		
		$dateDraw = new Zend_Date($xml->DrawResult->DrawDate);
		$dateDb = new Zend_Date($draws[0]['draw_date']);

		// draw results available
		if($dateDraw == $dateDb)
		{
			ll("waiting for new draw data...");
			
			$draw_id = $draws[0]['draw_id'];
			
			$date = new Zend_Date( $xml->DrawResult->NextDrawDate  );

//exit;			
			$arrData = Array(
			"draw_id"=>($draw_id+1),
			"jackpot" => 0,
			"message" =>"",
			"draw_date" => $date->toString("Y-M-d"),
			"big_winner" => 0,
			"published"=>0
			);
						
			try
			{
				ll("insert new draw ".($draw_id+1));
				$obj_ld->insert($arrData);
			} catch(EXCEPTION $e)
			{
				echo $e;
				exit;			
			}								
			
			// publish current draw
			$arrData = Array(			
			"published"=>1
			);
			try
			{
				$obj_ld->update($arrData,"draw_id=".$draw_id);
				ll("published the current draw ".$draw_id);
			} catch(EXCEPTION $e)
			{
				ll("error by publishing current draw ".$draw_id);
				echo $e;
				exit;
			}
			
			// results 
			$standard=0;
			$luckystar=0;
			
			ll("storing results to draw ".$draw_id);
			foreach($xml->DrawResult->Numbers->DrawNumber as $number)
			{				
			
				if($number->Type=="Standard")
				{
					ll("storing standard for pos ".$standard);
					$standard++;
					$arrData = Array(
					"draw_id"=>$draw_id,
					"type"=>"standard",
					"pos"=>$standard,
					"number"=>(string) $number->Number
					);
					
				} elseif($number->Type=="LuckyStar")  {
					ll("storing result for type luckystar for pos ".$luckystar);
					$luckystar++;
					$arrData = Array(
					"draw_id"=>$draw_id,
					"type"=>"luckystar",
					"pos"=>$luckystar,
					"number"=>(string) $number->Number
					);
				}

				try
				{
					$obj_lr->insert($arrData);
					ll("stored");
				} catch(EXCEPTION $e)
				{	ll("error by storing");
				}
			}			
		}
		else
		{
	
			$dateDb = new Zend_Date($draws[1]['draw_date']);
			
			// winners and jackpot for last draw
			if($dateDb == $dateDraw)
			{
			
				if(
					$xml->DrawResult->Structure->Tier
					&& $draws[1]['message']==""
				)
				{
				
					$draw_id = $draws[1]['draw_id'];
					
					
					// update draw data with winner data
					$arrData = Array(
					"draw_id"=>$draw_id,
					"jackpot" => (string)$xml->DrawResult->TopPrize,
					"message" => (string) $xml->DrawResult->Message ,
					"big_winner" => (string)$xml->DrawResult->Structure->Tier->Winners,
					"published"=>1
					);
					
					try
					{
						ll("update draw with winner info");
						$obj_ld->update($arrData,"draw_id=".$draw_id);
					} catch(EXCEPTION $e)
					{
						echo $e;
						exit;
					}
				
					// check if numbers availbale
					$no_results=true;
					
					foreach($xml->DrawResult->Structure->Tier as $winner)
					{									
						if($winner->Winners>0)
						{
							$no_results=false;
							ll("there are winners available now");
							break;
						}
					}
					
					// winners
					if($no_results==false)
					{					
						foreach($xml->DrawResult->Structure->Tier as $winner)
						{									
							// winners
							
							
							$type = (string) $winner->Match;												
							if(strlen($type)==3)
							{
								// with licky stars
								$arrTypes = explode("+",$type);
								$numbers = $arrTypes[0];
								$luckystars = $arrTypes[1];
								$t="lucky number";
							} elseif(strlen($type) == 1)
							{
								// normal number
								$numbers=$type;
								$luckystars=0;
								$t="normal number";
							}
							//echo $type;
							//exit;

							$arrData = Array(
							"draw_id"=>$draw_id,
							"winners"=>(string) $winner->Winners,
							"prize"=>(string) $winner->Prize,
							"numbers"=>$numbers,
							"luckystars"=>$luckystars,
							);

							try
							{
								$obj_lw->insert($arrData);
								ll("Winner fpr Number ".$numbers." and lucly star ".$luckystars." stored for draw ".$draw_id);
							} catch(EXCEPTION $e)
							{
								//ll("error by inserting");
								
							}						
						}
					} else {
						ll("no results available at the moment");
					}
					
					// update newest draw with next jackpot
					ll("jackpot check");
					$jackpot = $obj_w->getJackpot();
					if($jackpot>0)
					{
						ll("jackput > 0");
						$arrData = Array(
						"jackpot"=>$jackpot
						);
						try
						{
							$obj_ld->update($arrData,"draw_id=".($draw_id+1));
						} catch (EXCEPTION $e)
						{
							echo $e;
							exit;
						}
					}
					else
					{
						ll("jackpot for next draw not available now");
					}
					
				}
				else
				{
					ll("nix zu tun");
					exit("ASD2");
				}
			}
			else
			{
				exit("error 1");
			}
			exit;
		}
		
		exit;
		foreach($xml as $k1=>$data)
		{		
			//print_r($data);
			echo $data->NextDrawDate;
			exit;
	
		
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
		$obj_lw = new Lottery_Model_Euromillions_Winners();
		$obj_lr = new Lottery_Model_Euromillions_Results();

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
	
	 public function addresultsAction()
	 {
		$request = $this->getRequest();
		
		if($request->isPost())
		{
			$data = $request->getPost();
			print_r($data);
			exit;
		}

	}

	public function addjackpotAction()
	{
	}

}

function ll($msg)
{
	$fh = fopen(APPLICATION_PATH."/../lottery.log","a+");
	fputs($fh,date("d-m-Y G:i:s")." - ".$msg."\r\n");
	fclose($fh);
	echo $msg."<br>";
}