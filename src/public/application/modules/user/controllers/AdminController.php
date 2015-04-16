<?php

class User_AdminController extends Zend_Controller_Action
{

    public function init()
    {
        if(!hasAccess("acp") || !hasAccess("acp_user"))
		{
			$this->redirect($this->view->Url(Array(),"NoAccess",true));
		}
    }

	public function importAction()
	{
		$obj_u=new User_Model_User();
		$obj_ud=new User_Model_User_Details();
		$obj_urd=new User_Model_User_RegistrationDetails();


		$obj_u->delete("user_id>31");
		$obj_ud->delete("user_id>31");
		$obj_urd->delete("user_id>31");
//		exit("AA");

		$i=0;
		//$fh = fopen("raw/user.csv","r");
		//while( $row = fgets($fh,4096) )

		$str=file_get_contents("raw/user.csv");

		$arr=explode("\n",$str);

		$f=0;
		$o=0;
		$x=0;
		foreach($arr as $row)
		{
			if($i > 0)
			{
				//$row=trim($row,'"');
				$arrTmp=explode('";"',$row);

				if( count($arrTmp) == 18)
				{
					$arrTmp[0]=trim($arrTmp[0],'"');
					$arrTmp[17]=trim($arrTmp[17],'"');

					$o++;
					if($arrTmp[5])
					{
						$date = new Zend_Date( $arrTmp[5],'HH:mm:ss dd-MM-yyyy');
						$last_login=$date->toString('yyyy-MM-dd HH:mm:ss');
					} else {
						$last_login="";
					}


					if($arrTmp[10]<>"" && $arrTmp[9]<>"")
					{
						$str = $arrTmp[10]."_".strtoupper($arrTmp[9]);
					} else {
						$str="en_GB";
					}

					$locale = new Zend_Locale($str);

					if($locale->isLocale($str,true,true))
					{
						//echo $str." - ".$locale;
						$arrUser['locale']=$locale->toString();
						$arrTmp[9]=$locale->getRegion();
						$arrTmp[10]=$locale->getLanguage();
					}
					else
					{
						//echo "A".$locale->findLocale($str);
						$tmpLocale = $locale->getLocaleToTerritory($arrTmp[9]);
						$locale = new Zend_Locale($tmpLocale);

						if($locale->isLocale($tmpLocale,true))
						{
							$locale=new Zend_Locale($tmpLocale);
							//echo "erkannt: ".$locale;
							$arrUser['locale']=$locale->toString();
							$arrTmp[9]=$locale->getRegion();
							$arrTmp[10]="en";
						} else {

							//print_r($arrTmp);
							//echo ("AA");
							$arrUser['locale']="Null";
							$locale=new Zend_Locale("en_GB");
							$arrTmp[9]="en";
							$arrTmp[10]="en";
						}
					}

					//echo $locale;
					//exit;
					try
					{
						$currency = new Zend_Currency($locale);
						$currency->setLocale($locale);											//echo $currency->getShortName();
						$curr=$currency->getShortName();

					}
					catch(EXCEPTION $e)
					{
						echo $locale;
						exit("A");
					}


					$arrUser=Array(
					"username"=>$arrTmp[2],
					"last_login"=>$last_login,
					"last_ip"=>$arrTmp[6],
					"active"=>1,
					"role"=>"user",
					"user_code"=>md5($arrTmp[2]),
					"locale"=>$arrUser['locale'],
					"lang"=>$arrTmp[10],
					"country"=>$arrTmp[9],
					"currency"=>$curr
					);

					try
					{
						$user_id = $obj_u->insert($arrUser);

						//$user_id=30;

						if($arrTmp[7])
						{
							$date = new Zend_Date( $arrTmp[7],'HH:mm:ss dd-MM-yyyy');
							$reg_date=$date->toString('yyyy-MM-dd HH:mm:ss');
						} else {
							$reg_date="";
						}

						if($arrTmp[14])
						{
							$date = new Zend_Date( $arrTmp[14],'dd-MM-yyyy');
							$birth_date=$date->toString('yyyy-MM-dd');
						} else {
							$birth_date="";
						}

						$userReg=Array(
						"user_id"=>$user_id,
						"registration_date"=>$reg_date,
						"email"=>$arrTmp[2],
						"ip"=>$arrTmp[6],
						"player_id"=>$arrTmp[8],
						"whitelabel"=>$arrTmp[3],
						"country"=>$arrTmp[9],
						"lang"=>$arrTmp[10],
						);
						//print_r($userReg);
						$obj_urd->insert($userReg);

						$userDetails=Array(
						"user_id"=>$user_id,
						"first_name"=>$arrTmp[0],
						"last_name"=>$arrTmp[1],
						"nickname"=>$arrTmp[4],
						"country"=>$arrTmp[9],
						"street"=>$arrTmp[11],
						"zip"=>$arrTmp[12],
						"city"=>$arrTmp[13],
						"birth_date"=>$birth_date,
						);
						//print_r($userDetails);

						$obj_ud->insert($userDetails);
					} catch(EXCEPTION $e)
					{
						echo $e;
						exit;
					}
				}
				else
				{
					$f++;
					//echo count($arrTmp);
					//exit;
					//echo $row;
					print_r($arrTmp);
					//exit;
					//echo "<br><br>";
				}
			}
			else
			{
				$row=trim($row,'"');
				$a=explode('";"',$row);
				print_r($a);
				echo "<br><br>";
			}
			$i++;
			if($i>1000)
			{
				//exit;
			}
			//exit;
		}
		echo $x;
		exit;
		//fclose($fh);
		echo $o." - ".$f;
		exit("A");

	}

	public function updateoldusersAction()
	{
		$obj_u = new User_Model_User();
		$obj_ud = new User_Model_User_Details();
		$obj_ur = new User_Model_User_RegistrationDetails();

		$users=$obj_u->fetchAll("user_id<1000");
		foreach($users as $u)
		{

			$arrDetails = Array(
			"user_id"=>$u->user_id,
			"first_name"=>$u->first_name,
			"last_name"=>$u->last_name,
			"country"=>"GB",
			"gender"=>$u->gender,
			);
			$obj_ud->delete("user_id=".$u->user_id);
			$obj_ud->insert($arrDetails);
			//print_r($arrDetails);

			$date = new Zend_Date();
			$date = $date->toString('yyyy-MM-dd HH:mm:ss');

			$arrReg = Array(
			"user_id"=>$u->user_id,
			"email"=>"aa@asd.de",
			"registration_date"=>$date,
			"lang"=>"en",
			"country"=>"GB",
			"currency"=>"EURO"
			);
			$obj_ur->delete("user_id=".$u->user_id);
			$obj_ur->insert($arrReg);
			//print_r($arrReg);
			//exit;
		}
	}

    public function indexAction()
    {
			$request = $this->getRequest();
			$objUsers = new User_Model_User();
			$select = $objUsers->select();
			$select->from(Array("u"=>"users"),'user_id');
			$arrLang = getAllLanguages("backend");
			$arrCountries = getSortedCountryList();
			$page=$request->getParam('page',1);
			$arrFormSearchParams = Array('quick_user'=>'','quick_id'=>'','username'=>'','ticket_id'=>'','transaction_id'=>'','firstname'=>'','lastname'=>'','lastname'=>'','country'=>'','language'=>'','role'=>'');
			$this->view->arrCountries = $arrCountries;
			foreach($arrLang as $lang){
				$newlang[$lang] = $this->view->translate('language_' . $lang);
			}
			$this->view->arrLanguages = $newlang;

			$params = $request->getParams();

			if($request->isPost()) {
				$params['page'] = $page;
				if(!empty($params)){
					foreach($params as $param => $value){
						if($value <> '' && $value != '-1'){
							$urlp[$param] = urlencode($value);
						}
					}
					$url = $this->view->url($urlp,NULL,true);
					$this->_redirect($url);
				}
			} else {
				unset($params['module'],$params['controller'],$params['action'],$params['lang']);
				if(!empty($params)){
					foreach($params as $key => $param){
						$searchParams[$key] = urldecode($param);
					}
				}
			}


				if(!empty($searchParams['quick_user'])){
					$select->where('username LIKE ?', '%'. $searchParams['quick_user'] .'%');
					$select->orWhere("user_id in (select user_id FROM user_details WHERE first_name LIKE '%" . $searchParams['quick_user'] .  "%' OR last_name LIKE '%" . $searchParams['quick_user'] .  "%') ");
				}

				if(empty($searchParams['quick_user']) && !empty($searchParams['quick_id'])){
					$select->where("user_id in (select user_id FROM transactions WHERE order_id LIKE '" . $searchParams['quick_id'] .  "' OR biller_transaction_id LIKE '" . $searchParams['quick_id'] .  "')");
					$select->orWhere("user_id in (select user_id FROM em_tickets WHERE em_ticket_id LIKE '" . $searchParams['quick_id'] .  "')");
				}

				if(empty($searchParams['quick_user']) && empty($searchParams['quick_id'])){

					if (!empty($searchParams['username'])){
						$select->where('username LIKE ?', '%'. $searchParams['username'] .'%');
					}
					if (!empty($searchParams['ticket_id'])){
						$select->where("user_id in (select user_id FROM em_tickets WHERE em_ticket_id LIKE '" . $searchParams['ticket_id'] .  "')");
					}
					if (!empty($searchParams['transaction_id'])){
						$select->where("user_id in (select user_id FROM transactions WHERE order_id LIKE '" . $searchParams['transaction_id'] .  "' OR biller_transaction_id LIKE '" . $searchParams['transaction_id'] .  "')");
					}
					if (!empty($searchParams['firstname'])){
						$select->where("user_id in (select user_id FROM user_details WHERE first_name LIKE '%" . $searchParams['firstname'] .  "%')");
					}
					if (!empty($searchParams['lastname'])){
						$select->where("user_id in (select user_id FROM user_details WHERE last_name LIKE '%" . $searchParams['lastname'] .  "%')");
					}
					if (!empty($searchParams['country_id'])){
						$select->where('country_id LIKE ?', $country_id);
					}
					if (!empty($searchParams['language'])){
						$select->where('language LIKE ?', $searchParams['language']);
					}
					if (!empty($searchParams['role'])){
						$select->where('role LIKE ?', $role);
					}
				}

			/** get page count / get totalcount  **/

			$select->order('user_id desc');
			$users = $objUsers->fetchAll($select)->toArray();

			$arrData = array_slice($users,(($page - 1)*50),50);

			$obj_um = new User_Manager();
			$arrUsers = Array();
			foreach($arrData as $dd => $u){
				$uid = $u['user_id'];
				$arrUsers[] = $obj_um->getUserData($u['user_id']);
			}
			$this->view->users = $arrUsers;

			$this->view->searchParams = array_merge($arrFormSearchParams,$searchParams);
			// Paginator
			$pager = new Zend_Paginator(new Zend_Paginator_Adapter_Array($users));
			$pager->setCurrentPageNumber($page);
			$pager->setItemCountPerPage(50);
			$this->view->paginator = $this->view->paginationControl(
				$pager,
				'Sliding',
				'default/pagination/pagination.phtml'
			);
			$this->view->countTotal = count($arrData);

    }

    public function editAction()
    {
	 		$request = $this->getRequest();
			$user_id = $request->getParam('user_id');

			$objUsers = new User_Model_User();
			$select = $objUsers->select();
			$select->where("user_id=?",$user_id);

			$user = $objUsers->fetchRow($select)->toArray();
			$form = new User_Form_User();

			/* get User Details */
			$objUserDetails = new User_Model_User_Details();
			$select = $objUserDetails->select();
			$select->where("user_id=?",$user_id);
			$userDetails = $objUsers->fetchRow($select)->toArray();


			$user = array_merge($user, $userDetails);
			$form->populate($user);

			$this->view->form = $form;
			$this->view->userData = $user;
	}

	public function detailsAction()
    {
		$request = $this->getRequest();
		$user_id = $request->getParam('user_id');

		/* get Userdata */
		$objUsers = new User_Model_User();
		$select = $objUsers->select();
		$select->where("user_id=?",$user_id);
		$user = $objUsers->fetchRow($select)->toArray();

		/* get User Details */
		$objUserDetails = new User_Model_User_Details();
		$select = $objUserDetails->select();
		$select->where("user_id=?",$user_id);
		$userDetails = $objUsers->fetchRow($select)->toArray();

		$obj_c=new Default_Model_Countries();
		$arrCountries = $obj_c->getAllCountries("frontend");

		$this->view->userData = array_merge($user, $userDetails);
		$this->view->countries = $arrCountries;
	}

	public function transactionsAction()
    {
		$request = $this->getRequest();
		$user_id = $request->getParam('user_id');

		$objUsers = new User_Model_User();
		$select = $objUsers->select();
		$select->where("user_id=?",$user_id);

		$user = $objUsers->fetchRow($select)->toArray();
		$this->view->userData = $user;

		$objTransactions = new Billing_Model_Transactions();
		$select2 = $objTransactions->select();
		$select2->where("user_id=?",$user_id);

		$transactions = $objTransactions->fetchAll($select2)->toArray();
		$this->view->transactions = $transactions;
	}

	public function ticketsAction()
    {
		$request = $this->getRequest();
		$user_id = $request->getParam('user_id');

		$objUsers = new User_Model_User();
		$select = $objUsers->select();
		$select->where("user_id=?",$user_id);

		$user = $objUsers->fetchRow($select)->toArray();
		$this->view->userData = $user;
	}

	public function loginAction()
   {
		$form = new User_Form_Login();
		$this->view->form = $form;
	}
}