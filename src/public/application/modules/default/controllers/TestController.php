<?php

class TestController extends Zend_Controller_Action
{

	public function oAction()
	{
		$obj_ud = new User_Model_User_Details();
		$obj_u = new User_Model_User();
		$select = $obj_u->select();
//		$select->where("username not like '%@leipzig.elboob.de'");
		//$select->where("username not like '%@telepronto.de'");
		//$select->where("username not like '%@panamedia.net'");
		//$select->where("username not like '%@telepronto.com'");
		//$select->where("username not like 'nikki.s@hotmail.fr'");
		$select->where("role=?","user");

		$users = $obj_u->fetchAll($select);
		echo $users->count()."<br>";
		//exit;
		$i=1;
		$amountList = Array(0,"15000000","30000000","50000000","75000000","100000000");

		foreach($users as $user)
		{


			$jackpot = $amountList[ rand(0,5) ]."<br>";

			$user->username="username".$user->user_id."@leipzig.elboob.de";
			$user->password=md5("username".$user->user_id);
			//print_r($user->toArray());
			//exit;
			$i++;

			//echo $user->username."<br>";

			//echo $obj_u->update ( $user->toarray() ,"user_id=".$user->user_id);
			$obj_ud->update(Array("jackpot"=>$jackpot),"user_id=".$user->user_id);
		}
		exit("o");

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
						$recurring = $data['recurring'];
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
	}
	public function stylesAction(){
		
	}

	public function valAction()
	{
		$v = new Tpl_Validator_Iban(Array("country"=>"DE"));
		$v->setCountry("DE");

		$b = new Tpl_Validator_Bic();
		$b->setCountry("DE");

		$f = new Billing_Form_Bankaccount();
		$f->iban->addValidator($v);
		$f->bic->addValidator($b);

		$request = $this->getRequest();
		if($request->isPost())
		{
			$post = $request->getPost();
			if($f->isValid($post))
			{

			} else {
				$f->populate($post);
			}
		}

		echo $f;


		exit;
	}
	public function test2Action()
	{
		$obj_emt = new Lottery_Euromillions_Ticket();

		$obj_emt->resetTicket();
		$obj_emt->addNumbers( Array(1,2,3,4,5) );
		$obj_emt->addStars( Array(1,2) );
		$obj_emt->setTuesday ( true);
		$obj_emt->setFriday( true);
		$obj_emt->setNumDraws( 9);
		$obj_emt->setRecurring( 0);
//echo $obj_emt->getTicketPrice();

		if($obj_emt->validateTicket())
		{
			print_r($obj_emt->getDrawDates());
			$list = $obj_emt->getRealTicketList();
			print_r($list);
		}
		exit("X");
	}

	public function testmailAction()
	{
		$user_id=1;

		$um = new User_Manager($user_id);
		$userData = $um->getuserData($user_id);

		$obj_m = new Mail_Manager();
		$obj_m->setuser($user_id);

		// registration mail / activation
		$params=Array(
		"first_name"=> $userData['first_name'],
		"username"=>$userData['username'],
		"password"=>$userData['password'],
		"user_code"=>$userData['user_code'],
		);
		$obj_m->sendRegistration($params);

		$obj_m->sendForgotPassword ("test_password");

		$obj_m->sendWelcome();

		exit("ok");


	}

	public function mailAction()
	{
		$this->_helper->layout->disableLayout();
		//$this->_helper->viewRenderer->setNoRender();
		$this->view->setScriptPath(APPLICATION_PATH."/../templates/mails/");


		$request = $this->getRequest();
		$template = $this->getParam('templ',false);
		$handle=opendir (APPLICATION_PATH."/../templates/mails/");
		//echo readdir($handle);
		$arrFiles[] = 'Please select a template';
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && $file != "a-mail-menu-wrapper.phtml" && $file != "mail_footer.phtml" && $file != "mail_header.phtml" ) { $arrFiles[] = $file;}
		}
		closedir($handle);
		$this->view->templates = $arrFiles;
		$this->view->selected = $template;



		$this->view->first_name = 'Gunter';
		$this->view->user_code = 'akjsh2j34jhkjhfkj2h34';
		$this->view->username = 'asdasd@asdasdasd.de';
		$this->view->password = '1234liebe';
		$this->view->new_pw = '1234liebe';
		$this->view->draw_date = '11th of December 2014';
		$this->view->numbers = array('01','02','15','50','12');
		$this->view->stars = array('01','10');
		$this->view->est_jackpot = '58000000';
		$this->view->amount = '35.00';

		echo $this->view->render("/a-mail-menu-wrapper.phtml");
		if ($template){
			echo $this->view->render("/" . $template);
		}

		exit;
	}


    public function testAction()
	{

		echo $this->view->Lottery_Euromillions()->getnextDrawDate();
	exit;

		$obj_emt = new Lottery_Euromillions_Ticket();
		$obj_emt->addNumbers(Array(1,2,3,4,5));
		$obj_emt->addStars(Array(1,2));
		$obj_emt->friday = true;
		//$obj_emt->tuesday=true;

		if( $obj_emt->validateTicket() )
		{
			//echo $obj_emt->getNextPossibleDrawDate();
			print_r( $obj_emt->getDrawDates() );
		}
		exit("A");
	}


}