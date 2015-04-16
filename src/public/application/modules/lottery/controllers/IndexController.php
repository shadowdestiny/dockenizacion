<?php

class Lottery_IndexController extends Zend_Controller_Action
{

    public function init()
    {
		$request = $this->getRequest();
		$ajax = $request->getParam('ajaxload',false);
		if (!hasAccess("lottery")){
			if ($ajax){
				$notification = $this->view->Notification('notice_permission_denied',true);
				$response = Array("success" => false, "html" => $notification);
				echo json_encode($response);exit;
			} else {
				$this->redirect($this->view->Url(Array(),"NoAccess",true));
			}
		}
    }

public function aAction()
{
	//@Micha checken!!!
	$obj_ad = new Article_Model_ArticleDetails();
	$data = $obj_ad->fetchAll();
	foreach($data as $item)
	{
		if($item->image<>"")
		{
			//$item->image=str_replace("images/http://www.top15poker.com/img/","",$item->image);

			$path = APPLICATION_PATH."/../templates/default".$item->image;
			$path= strtolower($path);
			$path = str_replace("http://euromillions.com","",$path);

			if(file_exists($path))
			{

				$file = pathinfo($path);

				$dest = APPLICATION_PATH."/../media/article/".$item->article_id."/";
				@mkdir ($dest);

				copy ($path,$dest.$item->lang.".jpg");
				$obj_ad->update(Array( "image"=>"" ) ,"id=".$item->id);
				@unlink($path);
			}
			else
			{
				echo $item->image;
				echo "<br><br>";
			}
		}
	}

	exit;

	$obj_nd = new News_Model_NewsDetails();
	$select=$obj_nd->select();
	$select->where("id not in (?)",Array(54,62,63,69,71,73,75,76,80,81,83,85,86,87,88,90,95,96,97,98,100,101,102,104,105,106,107,108,109,111,112,114,113));

	$data = $obj_nd->fetchAll($select);
	foreach($data as $item)
	{
		if($item->image<>"")
		{
			//$item->image=str_replace("images/http://www.top15poker.com/img/","",$item->image);

			$path = APPLICATION_PATH."/../templates/default".$item->image;

			if(file_exists($path))
			{
				//echo "A";
				$file = pathinfo($path);

				$dest = APPLICATION_PATH."/../media/news/".$item->news_id."/";
				@mkdir ($dest);

				copy ($path,$dest.$item->lang.".jpg");
				$obj_nd->update(Array( "image"=>"" ) ,"id=".$item->id);
				@unlink($path);
			}
			else
			{
				echo $item->id;
				echo "<br><br>";
				echo $path;

				$path = APPLICATION_PATH."/../templates/default".strtolower( $item->image);
				$path = str_replace("http://euromillions.com","",$path);

				if(file_exists($path))
				{
					//echo "A";
					$file = pathinfo($path);

					$dest = APPLICATION_PATH."/../media/news/".$item->news_id."/";
					@mkdir ($dest);

					copy ($path,$dest.$item->lang.".jpg");
					$obj_nd->update(Array( "image"=>"" ) ,"id=".$item->id);
					@unlink($path);
				}

			}
		}
	}

	exit;
}
	public function indexAction()
	{

		$obj_ld = new Lottery_Model_Euromillions_Draws();
		$obj_lr = new Lottery_Model_Euromillions_Results();
		$obj_lw = new Lottery_Model_Euromillions_Winners();

		$request = $this->getRequest();

		$type = $request->getParam("type","index");

		if($type=="index")
		{
			$page = $request->getParam("ajaxpage",0);
			if($page==0)
			{
				$page = $request->getParam("page",1);
			}

		} else {
			$date = $request->getParam("date","");
		}
		//$type="archive";
		//$date="2013-09";

		$select = $obj_ld->select();
		$select->where("published=?",1);
		if($type == "archive")
		{
			$select->where("draw_date like (?)","%".$date."%");

			$arrDate = explode("-",$date);
			$this->view->year = $arrDate[0];
			$this->view->month = $arrDate[1];
		}


		$select->order("draw_id desc");

		$arrDraws = $obj_ld->fetchAll($select);

		$this->view->countTotal = $arrDraws->count();



		$data = $arrDraws->toArray();

		if($type == "index")
		{
			$this->view->page = $page;

			$paginator = Zend_Paginator::factory($data);
			$paginator->setItemCountPerPage(10);
	        $paginator->setCurrentPageNumber($page);
			$this->view->pageCount = $paginator->getPages()->pageCount;
			$this->view->currentPage = $page;
			$this->view->paginator = $this->view->paginationControl(
					$paginator,
					'Sliding',
					'default/pagination/pagination.phtml'
					);

			$list = array_slice($data,(($page-1)*$paginator->getItemCountPerPage() ), $paginator->getItemCountPerPage());
		}
		else
		{
			$list = $data;
		}

		$date = new Zend_Date();
		$this->view->current_year = $date->get(Zend_Date::YEAR);


		$this->view->type=$type;

		if($list)
		{
			foreach($list as $item)
			{
				$arrList[] = $item['draw_id'];
			}
		}

		$this->view->list = $arrList;

		$obj_s = new Default_Model_Stats();

		if($request->getParam("ajaxload",0)){
			$obj_s->set("show_em_results_ajaxload");
			$this->view->ajaxload=1;
			$html = $this->view->render("index/index.phtml");
			echo json_encode(Array('success' => true,'html' =>$html,'pagination' => $this->view->paginator ));
			exit;
		} else {
			$obj_s->set("show_em_results");
		}
		//echo $this->view->Lottery_Euromillions()->getJackpot();
		//$d = $this->view->Lottery_Euromillions()->getWinner(692);
		//print_r($d);
		//exit;
	}

	public function showAction()
	{

		$request = $this->getRequest();

		$draw_id = $request->getParam("draw_id",0);
		$draw_date = $request->getParam("date","");

		if($draw_id>0 && $draw_date<>"")
		{
			$this->view->draw_id = $draw_id;
			$obj_s = new Default_Model_Stats();
			$obj_s->set("show_em_results_single");
		} else  {
			$this->redirect($this->view->baseUrl());
		}



	}

	public function checkAction()
	{
		$obj_ld = new Lottery_Model_Euromillions_Draws();
		$obj_lr = new Lottery_Model_Euromillions_Results();
		$obj_lw = new Lottery_Model_Euromillions_Winners();

		$select = $obj_ld->select();
		$select->where("draw_date>=?","2014-01-01");
		$select->where("draw_date<=?","2014-12-31");
		$select->order("draw_id");

		$draws = $obj_ld->fetchAll($select);

//		$numbers=Array(11,1,50,38,12,3,9);
		//$numbers=Array(38,37,49,11,1,2,3);

		//$arrNumbers[] = Array(50,4,25,38,12,2,8);
		//3 8,  4 7

//		$arrNumbers[] = Array(42,21,23,40,16,7,1);
		//$arrNumbers[] = Array(11,50,4,25,38,9,2);
		//$arrNumbers[] = Array(37,49,14,42,21,8,5);
		//$arrNumbers[] = Array(1,11,50,4,25,3,9);

		//$arrNumbers[]=Array(1,7,13,19,25,3,9);
		//$arrNumbers[]=Array(26,32,38,44,50,3,9);
		//$arrNumbers[]=Array(30,34,38,42,46,3,9);
		$arrNumbers[]=Array(4,13,28,38,42,10,2);
		$arrNumbers[] = Array(19,27,29,31,44,11,2);
		$arrNumbers[] = Array(13,24,27,35,38,10,11);

		$arrNumbers[]=Array(4,18,21,26,27,7,10);
		$arrNumbers[]=Array(7,8,15,43,50,4,6);
		$arrNumbers[]=Array(13,17,25,26,37,6,11);


		// million
		//$arrNumbers[] = Array(11,38,15,41,43,2,6);

		foreach($draws as $draw)
		{

			foreach($arrNumbers as $numbers)
			{
				echo $draw->draw_date." - ";
				$myResult = $this->getWinResultForDraw($draw->draw_id,$numbers);
				if($myResult)
				{
					echo $myResult['cost']." - ";
					$cost = ($cost + $myResult['cost']);
					echo $myResult['win']." - ";
					$win = ($win + $myResult['win']);
					echo $myResult['standards']." - ";
					echo $myResult['luckystars'];
				}


				echo "<br>";
			}
		}
		echo "Gesamtkosten: ".$cost." - Gewin: ".$win;

		exit;
	}

	public function getWinResultForDraw($draw_id,$numbers)
	{
		$obj_lr = new Lottery_Model_Euromillions_Results();
		$obj_lw = new Lottery_Model_Euromillions_Winners();

		if($draw_id>0 && count($numbers)==7)
		{
			$arrStandard = array_slice ($numbers,0,5);
			$arrLuckystars = array_slice ($numbers,5,2);

			$select = $obj_lr->select();
			$select->where("draw_id=?",$draw_id);
			$select->where("type=?","standard");
			$select->where("number in (?)",$arrStandard);
			$standards = $obj_lr->fetchAll($select)->count();

			$select = $obj_lr->select();
			$select->where("draw_id=?",$draw_id);
			$select->where("type=?","luckystar");
			$select->where("number in (?)",$arrLuckystars);
			$luckystars = $obj_lr->fetchAll($select)->count();

			if($standards>=0)
			{
				$select = $obj_lw->select($select);
				$select->where("draw_id=?",$draw_id);
				$select->where("numbers=?",$standards);
				$select->where("luckystars=?",$luckystars);
				$winners = $obj_lw->fetchRow($select);
				if($winners)
				{
					$prize = $winners->prize;
				} else {
					$prize = 0;
				}
			}
			else
			{
				$prize=0;
			}
			$arrData = Array(
			"cost"=>2.30,
			"win" => $prize,
			"standards"=>$standards,
			"luckystars"=>$luckystars,
			);
		}

		return $arrData;


	}

}