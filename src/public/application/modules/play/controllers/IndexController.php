<?php

class Play_IndexController extends Zend_Controller_Action
{

    public function init()
    {
		$request = $this->getRequest();
		$this->popup = $request->getParam('popup',false);
		if (!hasAccess("play")){
			if ($this->popup){
				$notification = $this->view->Notification('notice_permission_denied',true);
				$response = Array("success" => false, "html" => $notification);
				echo json_encode($response);exit;
			} else {
				$this->redirect($this->view->Url(Array(),"NoAccess",true));
			}
		}

        //$this->_helper->layout->disableLayout();
    }


	public function indexAction()
	{
	
	

		$obj_led = new Lottery_Euromillions_Draw();
		$nextDrawDates = $obj_led->getNextDrawDates(8);
		$this->view->nextDrawDates = $nextDrawDates;

		$lottery = "euromillions";

		$request = $this->getRequest();

		if($request->isPost() && $request->getPost("act")=="play")
		{
			$obj_cart = new Logic_Cart();

			$data = $request->getPost();

			$items = Array();

			if(isset($data['numbers']) && isset($data['numbers']))
			{


				$obj_emt = new Lottery_Euromillions_Ticket();

				$post_id = time();

				$obj_t = new Lottery_Model_Euromillions_Ticket();

				foreach($data['numbers'] as $i => $item)
				{
					// check if line has stars
					if(isset($data['stars'][$i]))
					{
						// validate numbers
						$arrNumbers = explode(",",$data['numbers'][$i]);
						$arrStars = explode(",",$data['stars'][$i]);						
						$recurring = (isset($data['recurring'])? $data['recurring']:0);
						$nextDrawDate = new Zend_Date($data['nextdraw']);

						$obj_emt->resetTicket();

						if( $obj_emt->validateTicketNumbers($arrNumbers,$arrStars) )
						{
							$ticket_type = $obj_emt->getTicketTypeByNumbers($arrNumbers,$arrStars,$recurring);

							$obj_emt->addNumbers($arrNumbers);
							$obj_emt->addStars($arrStars);

							if(isset($data['tuesday']))
							{
								$obj_emt->setTuesday(1);
							} else {
								$obj_emt->setTuesday(0);
							}

							if(isset($data['friday']))
							{
								$obj_emt->setFriday(1);
							} else {
								$obj_emt->setFriday(0);
							}

							if(isset($data['weeks']) && $data['weeks']>1)
							{

								$numOfDraws =( ($data['tuesday'] + $data['friday'])  * $data['weeks']);
								$obj_emt->setNumDraws($numOfDraws);
								//print_r($_POST);
								//exit;
							}
							else
							{
								$obj_emt->setNumDraws(1);
							}

							if(isset($data['recurring']) && $data['recurring']==1)
							{
								$obj_emt->setRecurring(1);
							} else {
								$obj_emt->setRecurring(0);
							}

							$obj_emt->setStartDrawDate($nextDrawDate);

							if($obj_emt->validateTicket())
							{

								if(!isset($cart_id))
								{
									$cart_id = $obj_cart->getCartId();
								}

								if ( $obj_cart->addItemToCart($cart_id,$post_id,$lottery,$obj_emt) )
								{
									//mfg
									// Stats
									$obj_s = new Default_Model_Stats();
									$obj_s->set("add_ticket_to_cart");


								}
								else
								{
									//mfg
								}
								$obj_cart->updateCart($cart_id);
							}
							else
							{
								alert("Error by Validating Ticket: ".serialize($obj_emt));
							}
						}
					}
				}
// mfg
//exit;
				$this->redirect($this->view->Url(Array(),"Billing_Cart"));
			}
exit;
			exit("done");
			// wenn es daten gibt, zum warenkorb hinzufügen
			if($items)
			{
				$obj_cart = new Logic_Cart();
				$cart_id = $obj_cart->getCartId();

				if($cart_id>0)
				{
					$post_id = time();

					foreach($items as $item)
					{

						if( $obj_c->addItemToCart($cart_id,$post_id,$lottery,$item['obj'],$item['price']) )
						{
							echo "ja";
						} else {
							exit("nein");
						}
					}
				} else {
					alert("Cart_Id=0!");
					exit;
				}

				$obj_c->updateCart($cart_id);



			}

			//echo count($items);
			//print_r($items);

		}

		// Stats
		$obj_s = new Default_Model_Stats();
		$obj_s->set("show_play_em_page");

		

		// ermitteln der nächsten ziehung
		$obj_lottery_em_draws = new Lottery_Euromillions_Draw();
		//echo $obj_lottery_em_draws->getNextDrawDate();
		//echo $obj_lottery_em_draws->getNextDrawId();
		$this->view->draw_day = $obj_lottery_em_draws->getNextDrawDay();
		//echo $obj_lottery_em_draws->isInTimeLimit();
		$this->view->timeLeft = $obj_lottery_em_draws->timeToNextDraw();

	}

	public function multipletipajrAction()
	{

		$request = $this->getRequest();
		$popup = $request->getParam('popup', false);
		$this->view->popup = $popup;

		if($request->isPost()){
			// Stats
			$obj_s = new Default_Model_Stats();
			$obj_s->set("show_play_em_multipletickets_overlay");

			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();

			$data = $request->getPost();
			$arrNumbers = $this->view->Play_Tickets()->getMultiplePriceList();

			/*
			foreach($arrNumbers as $stars => $value){
				foreach($value as $number => $prices){
					$arrPrices[$number][$stars] = $prices;
				}
			} */


			$this->view->line = ($data['line']) ? $data['line'] : '';
			$this->view->arrPrices = $arrNumbers;

			$html = $this->view->render("play/index/multipletipajr.phtml");
			$response=Array("success"=>true,"html"=>$html);
			echo json_encode($response);
		} else {
			exit('error');
		}

	}

	public function quickhelpajrAction()
	{

		$request = $this->getRequest();
		$popup = $request->getParam('popup', false);
		$this->view->popup = $popup;

		if($request->isPost()){
			// Stats
			$obj_s = new Default_Model_Stats();
			$obj_s->set("show_play_em_quickhelp_overlay");

			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();

			$html = $this->view->render("play/index/quickhelpajr.phtml");
			$response=Array("success"=>true,"html"=>$html);
			echo json_encode($response);
		} else {
			exit('error');
		}

	}

}