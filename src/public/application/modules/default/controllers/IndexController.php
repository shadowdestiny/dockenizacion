<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
/*
		$obj_currency = new Zend_Currency();
		//$obj_currency->setLocale("ru_RU");
		$obj_currency->setService("Tpl_CurrencyConvert");
		$obj_currency->setValue("4.95","EUR");
		echo $obj_currency;

		exit;
	 $obj_currency = Zend_Registry::get("Zend_Currency");
		$currency = clone $obj_currency;

		$currency->setValue(4.95,"EUR");

		echo $currency->getRatio("EUR","USD");

	 exit;
*/
    }


	public function index3Action()
	{
		$obj_s = new Default_Model_Stats();
		$types = $obj_s->getStatsTypes();
		echo $obj_s->set("register_step3",1);
		print_r($types);

		exit;
	}
	public function indexAction()
	{
		//$obj_c = new Default_Model_Countries();
		//echo $obj_c->importCountries();
		//exit;
	/*
	$obj_c=new Default_Model_Countries();

	$cl = getCountryList();
	foreach($cl as $c=>$n)
	{
		$arrCountry=Array(
		"short_code"=>$c,
		"name"=>$n,
		"active_registration"=>0,
		"active_payout"=>0
		);
		$obj_c->insert($arrCountry);
	}
	echo count($cl);
	exit("A");
	*/


		//echo $this->view->Currency()->get(2.35);
		//exit;
		//exit;
		//$obj_c = new Default_Model_Currencies();
		//$cl = $obj_c->getCurrencyList();
		//print_r($cl);
		//echo $this->view->Play_Tickets()->getMultiplePriceList();

		$this->view->home = true;

		// Stats
		$obj_s = new Default_Model_Stats();
		$obj_s->set("show_home");
	}

	public function newsAction()
	{
		//@Micha check mal den scheiß
		exit();

		$obj_t= new Default_Model_Translations();
		$db = $obj_t->getAdapter();

		$obj_n = new News_Model_News();
		$obj_nd = new News_Model_NewsDetails();



		$sql="select * from articles_orig where pageType=2";
		//$sql.=" and LanguageId=4";
		$sql.=" order by Created";

		$data = $db->fetchAll($sql);
		//echo count($data);
		foreach($data as $item)
		{
			$pd = dd($item['PublishedDate']);;
			$cd = dd($item['Created']);

			$select = $obj_n->select();
			$select->where("`key`=?",$item['Key']);

			$news = $obj_n->fetchRow($select);

			$pubDate = new Zend_Date($pd);
			$creationDate = new Zend_Date($cd);

			if($news)
			{

				$news_id= $news->news_id;
			} else {


				$arrData = Array(
				"published" => $item['Published'],
				"published_on" => $pubDate->get(Zend_Date::TIMESTAMP),
				"published_by_name" => $item['Author'],
				"creation_date" => $creationDate->get(Zend_Date::TIMESTAMP),
				"key" => $item['Key'],
				);

				try
				{
					$news_id = $obj_n->insert($arrData);
				} catch(EXCEPTION $e)
				{
					echo $e;
					exit;
				}

			}




			$arrData = Array(
			"news_id"=>$news_id,
			"alias" => make_alias($item['Title']),
			"title" => $item['Title'],
			"content" => $item['Content'],
			"page_title"=> $item['PageTitle'],
			"meta_keywords" => $item['MetaKeywords'],
			"meta_description" => $item['MetaDescription'],
			"published" => $item['Published'],
			"published_on" => $pubDate->get(Zend_Date::TIMESTAMP),
			"published_by_name" => $item['Author'],
			"creation_date" => $creationDate->get(Zend_Date::TIMESTAMP),
			"key" => $item['Key'],
			"header"=>$item['Header'],
			"image"=>$item['Image'],
			"alt"=>$item['Alt'],
			"description"=>$item['Description'],
			);


			switch($item['LanguageId'])
			{
				case 1:
					$lang="en";
					break;
				case 2:
					$lang="fr";
					break;
				case 3:
					$lang="es";
					break;
				case 4:
					$lang="de";
					break;
				case 7:
					$lang="sw";
					break;
				default:
					$lang="Null";
					break;
			}
			$arrData ['lang']= $lang;

			try
			{
				$obj_nd->insert($arrData);
				//exit;
			 } catch (EXCEPTION $e)
			 {
				//echo $e;
				//echo $item['Title']." - ".$lang."<br>";
				echo "d<br>";
				//exit;
			 }
			//print_r($arrData);
			//exit;
		}

		exit;
	}


	public function articleAction()
	{
		//@Micha check mal den scheiß
		exit();

		$obj_t= new Default_Model_Translations();
		$db = $obj_t->getAdapter();

		$obj_a = new Article_Model_Articles();
		$obj_ad = new Article_Model_ArticleDetails();

		$sql="select * from articles_orig where pageType=1";
		//$sql.=" and LanguageId=4";
		$sql.=" order by Created";

		$data = $db->fetchAll($sql);
		//echo count($data);
		foreach($data as $item)
		{
			$pd = dd($item['PublishedDate']);;
			$cd = dd($item['Created']);

			$select = $obj_a->select();
			$select->where("`key`=?",$item['Key']);

			$article = $obj_a->fetchRow($select);

			$pubDate = new Zend_Date($pd);
			$creationDate = new Zend_Date($cd);

			if($article)
			{
				$article_id= $article->article_id;
			} else {


				$arrData = Array(
				"published" => $item['Published'],
				"published_on" => $pubDate->get(Zend_Date::TIMESTAMP),
				"published_by_name" => $item['Author'],
				"creation_date" => $creationDate->get(Zend_Date::TIMESTAMP),
				"key" => $item['Key'],
				);
				try
				{
					$article_id = $obj_a->insert($arrData);
				} catch(EXCEPTION $e)
				{
					echo $e;
					exit;
				}

			}

			//$pd = dd($item['PublishedDate']);;
			//$cd = dd($item['Created']);


			$arrData = Array(
			"article_id"=>$article_id,
			"alias" => make_alias($item['Title']),
			"title" => $item['Title'],
			"content" => $item['Content'],
			"page_title"=> $item['PageTitle'],
			"meta_keywords" => $item['MetaKeywords'],
			"meta_description" => $item['MetaDescription'],
			"published" => $item['Published'],
			"published_on" => $pubDate->get(Zend_Date::TIMESTAMP),
			"published_by_name" => $item['Author'],
			"creation_date" => $creationDate->get(Zend_Date::TIMESTAMP),
			"key" => $item['Key'],
			"header"=>$item['Header'],
			"image"=>$item['Image'],
			"alt"=>$item['Alt'],
			"description"=>$item['Description'],
			);


			switch($item['LanguageId'])
			{
				case 1:
					$lang="en";
					break;
				case 2:
					$lang="fr";
					break;
				case 3:
					$lang="es";
					break;
				case 4:
					$lang="de";
					break;
				case 7:
					$lang="sw";
					break;
				default:
					$lang="Null";
					break;
			}
			$arrData ['lang']= $lang;

			try
			{
				$obj_ad->insert($arrData);
				//exit;
			 } catch (EXCEPTION $e)
			 {
				echo $e;
				echo $item['Title']." - ".$lang."<br>";
				//exit;
			 }
			//print_r($arrData);
			//exit;
		}

		exit;
	}


    public function index2Action()
    {
		//@Micha check mal den scheiß
		exit();


		$x = file_get_contents("a.sql");
		$arr = explode(")\r\n",$x);
		echo count($arr);

		$obj_t= new Default_Model_Translations();
		$db = $obj_t->getAdapter();

		$fh=fopen("a2.sql","a+");

		foreach($arr as $sql)
		{
			try
			{
				//$sql=str_replace("INSERT  into articles_orig ","INSERT  into articles_orig set",$sql);
				//echo $sql;
				//exit;
				//$db->exec($sql);
				fputs($fh,$sql.");\r\n\r\n");
			} catch(EXCEPTION $e)
			{
				//echo $e;
				//exit;
			}


		}
		fclose($fh);
		exit;
		//$bootstrap = $this->getInvokeArg('bootstrap');
        //if (!$bootstrap->hasResource('Log')) {
            //exit;
        //}


	//echo $this->view->translate("country-code","es");
	echo $this->view->translate("latest-articles");


	exit;
		$obj_t = new Default_Model_Translations();
		$obj_td = new Default_Model_TranslationDetails();

		$select = $obj_t->getAdapter()->select();
		$select->from( Array("to"=>"translations_orig"),Array("*") );
		$data = $obj_t->getAdapter()->fetchAll($select);

		$d = $obj_t->getAdapter();
		$d->exec("
		TRUNCATE `translation_details` ;
		TRUNCATE `translations` ;
		");

		foreach($data as $item)
		{
			$arrData1 = Array(
			"key"=>$item['key']
			);
			try
			{
				$id = $obj_t->insert($arrData1);
			} catch(EXCEPTION $e)
			{
				$select = $obj_t->select();
				$select->where("`key`=?",$item['key']);
				$row = $obj_t->fetchRow($select);
				$id = $row['translation_id'];
			}


			switch($item['LanguageId'])
			{
				case 1:
					$lang="en";
					break;
				case 2:
					$lang="fr";
					break;
				case 3:
					$lang="es";
					break;
				case 4:
					$lang="de";
					break;
				case 7:
					$lang="sw";
					break;
				default:
					$lang="Null";
					break;
			}

			//print_r($item);
			//exit;

			//print_r($arrData1);

			$arrData2 = Array(
			"translation_id" => $id,
			"lang"=>$lang,
			"value"=>$item['value']
			);
			try
			{
				$obj_td->insert($arrData2);
			} catch(EXCEPTION $e)
			{

				//echo $item['LanguageId'];
				//echo $e;
				//exit;
				echo "d<br>";
			}



			//echo $item['value'];
			//exit;
		}

		exit("Asd");
        // action body
    }

	public function importcurrencyAction()
	{
		//@Micha check mal den scheiß
		$url = "http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml";
		try
		{
			$xml = file_get_contents($url);
			if($xml)
			{
				echo $xml;
			}
		} catch(EXCEPTION $e)
		{
			error_log("Error during import cron: ".$e);
			echo $e;
			exit;
		}

	}
}