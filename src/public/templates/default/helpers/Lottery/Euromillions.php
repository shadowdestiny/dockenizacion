<?

class Zend_View_Helper_Lottery_Euromillions
{
	public $view;

	public function Lottery_Euromillions()
	{
		return $this;
	}

	public function getJackpot()
	{
		$obj_ld = new Lottery_Model_Euromillions_Draws();
		$jackpot = $obj_ld->getNextJackpot();
		
		if( $jackpot )
		{
			$cur = Zend_Registry::get("Zend_Locale");
			$str = $this->view->Currency()->get($jackpot,0,true);

			$str = str_replace(",","'", $str);
			$str = str_replace(".","'", $str);
			return  $str;
		}
		else
		{
			return "";
		}
	}

	public function getResult($draw_id)
	{
		$obj_ld = new Lottery_Model_Euromillions_Draws();
		$obj_lr = new Lottery_Model_Euromillions_Results();

		$select = $obj_ld->select();
		$select->where("draw_id=?",$draw_id);
		$select->where("published=?",1);
		$draw = $obj_ld->fetchRow($select);

		if($draw)
		{

			if($draw->jackpot>0)
			{
				$cur = Zend_Registry::get("Zend_Locale");
				$str = $this->view->Currency()->get($draw->jackpot,0);
				$jackpot = $str;
			}
			else
			{
				$jackpot="";
			}

			$arrResult = Array(
			"draw_id" => $draw->draw_id,
			"draw_date" => $draw->draw_date,
			"jackpot"=>$jackpot,
			"winner"=>$draw->big_winner,
			"detail_link"=>$this->view->Url( Array("draw_id"=>$draw->draw_id,"date"=>$draw->draw_date),"Lottery_Show" )
			);

			$select = $obj_lr->select();
			$select->where("draw_id=?",$draw->draw_id);

			$numbers = $obj_lr->fetchAll($select);
			if($numbers->count()==7)
			{
				foreach($numbers as $number)
				{
					$arrResult[$number['type']."_".$number['pos']] = $number['number'];
				}
			}
			else
			{
				return Array();
			}
			return $arrResult;
		} else {
			return Array();
		}
		return "ASDASD";
	}

	public function getLastResults()
	{
		$obj_ld = new Lottery_Model_Euromillions_Draws();

		$select = $obj_ld->select();
		$select->where("draw_date<=?",date("Y-M-d"));
		$select->where("published=?",1);
		//$select->where("active=?",1);
		$select->order("draw_id desc");
		$draw = $obj_ld->fetchRow($select);

		if($draw)
		{
			return $this->getResult($draw->draw_id);
		} else {
			return Array();
		}

	}


	public function getWinner($draw_id)
	{
		if($draw_id>0)
		{
			$cur = Zend_Registry::get("Zend_Locale");

			$obj_lw = new Lottery_Model_Euromillions_Winners();

			$select = $obj_lw->select();
			$select->where("draw_id=?",$draw_id);
			//$select->order("numbers desc");
			//$select->order("luckystars desc");
			$select->order("prize desc");


			$data = $obj_lw->fetchAll($select);
			if($data)
			{
				foreach($data as $item)
				{
					$prize = $this->view->Currency()->get($item['prize']);


					$winners = $this->view->Formattext()->winners_number($item['winners']);

					//$arrWinners [ $item->numbers ] [$item->luckystars] ['prize'] = $prize;
					//$arrWinners [ $item->numbers ] [$item->luckystars] ['winners'] = $winners;



					$w['numbers'] = $item->numbers;
					$w['luckystars'] = $item->luckystars;
					$w['prize'] = $prize;
					$w['winners'] = $winners;

					$arrWinners[] = $w;
				}

				return $arrWinners;
			}
			else
			{
				return Array();
			}
		}
		return false;
	}

	public function getTimeLeftNextDraw()
	{
		$obj_lottery_em_draws = new Lottery_Euromillions_Draw();
		return $obj_lottery_em_draws->timeToNextDraw();
	}

	public function getNextDrawDate()
	{
		$obj_lottery_em_draws = new Lottery_Euromillions_Draw();
		return $obj_lottery_em_draws->getNextDrawDate();
	}

	public function setView(Zend_View_Interface $view)
	{
		$this->view = $view;
	}
}
