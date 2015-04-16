<?php

class CronController extends Zend_Controller_Action
{

    public function init()
    {		
		/*
		$form = new User_Form_Registration();
		$r=$this->getRequest();
		if($r->isPost())
		{
			$data = $r->getPost();
			if( $form->isValid($data) )
			{
				$post = $form->getValues();
				print_r($post);
				exitt;
			} else {
				$form->populate($data);
				echo $form;
			}
			exit;
		}
		echo $form;
		echo $this->view->Currency()->get("1.00");
		exit("b");;
		*/

    }

		
	public function importcurrencyAction()
	{
		$url = "http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml";
		try
		{
			$obj_c = new Default_Model_Currencies();
						
			$XML=simplexml_load_file("http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml");
            
			foreach($XML->Cube->Cube->Cube as $rate)
			{
				if(
					is_numeric( (string)$rate["rate"] )
					&& (string)$rate["currency"]<>""
				)
				{
					$arrData=Array(
					"currency"=>(string)$rate["currency"],
					"rate"=>(string)$rate["rate"],
					"last_update"=>Zend_Date::now()->toString('yyyyMMddHHmmss')
					);
					
					$select = $obj_c->select();
					$select->where("currency=?",(string)$rate["currency"]);
					$data = $obj_c->fetchRow($select);
					if($data)
					{
						$obj_c->update($arrData,"id=".$data->id);
					} else {
						$obj_c->insert($arrData);
					}
				
					//echo '1&euro;='.$rate["rate"].' '.$rate["currency"].'<br/>';        
					//$rate["rate"] and $rate["currency"] into your database
				}				
			}
			
			// cache leeren
			$cache = Zend_Registry::get("Zend_Cache");
			$cache_id = "currency_rates";
			$cache->remove($cache_id);
			
		} catch(EXCEPTION $e)
		{
			error_log("Error during import cron: ".$e);
			echo $e;
			exit;
		}
		exit;
	}
}