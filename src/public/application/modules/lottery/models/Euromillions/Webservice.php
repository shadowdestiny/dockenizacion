<?

class Lottery_Model_Euromillions_Webservice extends Zend_Db_Table_Abstract
{
	public function getResultForDate($date)
	{
		$url = "http://resultsservice.lottery.ie/ResultsService.asmx/GetResultsForDate";
		$qry_str = "drawType=EuroMillions&drawDate=".$date;

		$ch = curl_init();	
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, '3');
		curl_setopt($ch, CURLOPT_USERAGENT, "CURL");		
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $qry_str);
		$content = trim(curl_exec($ch));
		curl_close($ch);
		
		//echo $content;

		try
		{		
			$xml = @new SimpleXMLElement($content);
			return $xml;
		} catch(EXCEPTION $e)
		{			
			return Array();
		}	
	}
	
	
	public function getJackpot()
	{
		$url = "http://resultsservice.lottery.ie/ResultsService.asmx/GetProjectedJackpot ";
		$qry_str = "drawType=EuroMillions";

		$ch = curl_init();	
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, '3');
		curl_setopt($ch, CURLOPT_USERAGENT, "CURL");		
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $qry_str);
		$content = trim(curl_exec($ch));
		curl_close($ch);
		
		//echo $content;

		try
		{		
			$xml = new SimpleXMLElement($content);
			return $xml->Amount;			
		} catch(EXCEPTION $e)
		{
			
			exit($e);
			return Array();
		}	
	}
	
	public function getCompetitionAnswers($question_id)
	{
		$url = "http://resultsservice.lottery.ie/ResultsService.asmx/GetCompetitionAnswers ";
		$qry_str = "drawType=EuroMillions&QuestionID=".$question_id;

		$ch = curl_init();	
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, '3');
		curl_setopt($ch, CURLOPT_USERAGENT, "CURL");		
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $qry_str);
		$content = trim(curl_exec($ch));
		curl_close($ch);
		echo $content;
		exit("E");
		
		try
		{		
			$xml = new SimpleXMLElement($content);
			return $xml;
		} catch(EXCEPTION $e)
		{
			exit($e);
			return Array();
		}	
	}
	
	
	public function getResults2($count)
	{
		$url = "http://resultsservice.lottery.ie/ResultsService.asmx/GetResults";
		$qry_str = "drawType=EuroMillions&lastNumberOfDraws=".$count;

		$ch = curl_init();	
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, '3');
		curl_setopt($ch, CURLOPT_USERAGENT, "CURL");		
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $qry_str);
		$content = trim(curl_exec($ch));
		curl_close($ch);
/*

		//$post = "drawType=EuroMillions&drawDate=2013-09-24T00:00:00";
		$post = "drawType=EuroMillions&lastNumberOfDraws=98703";
		
		//&lastNumberOfDraws=10";

		//$ch = curl_init("resultsservice.lottery.ie/ResultsService.asmx/GetResults?");
		//$ch = curl_init("resultsservice.lottery.ie/ResultsService.asmx/GetCurrentSweepstakesResult ");
		$post="drawType=EuroMillions&lastNumberOfDraws=98612";

		//$ch = curl_init( "http://resultsservice.lottery.ie/ResultsService.asmx/GetCurrentSweepstakesResult");
		$ch = curl_init( "http://resultsservice.lottery.ie/ResultsService.asmx/GetResults");
		
		

	    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		//curl_setopt($ch, CURLOPT_USERPWD, $this->admin_username . ':' . $this->admin_password);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	    $curl = curl_exec($ch);
	    curl_close($ch);
		
		echo strlen($curl);
		echo $curl;
		exit;
	*/
		
		try
		{		
			$xml = new SimpleXMLElement($content);
			return $xml;
		} catch(EXCEPTION $e)
		{
			exit($e);
			return Array();
		}	
	}
	
	
	public function getResults3()
	{
		$post = "drawType=EuroMillions";

		$ch = curl_init("resultsservice.lottery.ie/ResultsService.asmx/GetCurrentSweepstakesResult ");
		//$ch = curl_init("resultsservice.lottery.ie/ResultsService.asmx/GetCurrentRaffleResult");
		//$ch = curl_init("resultsservice.lottery.ie/ResultsService.asmx/GetResults");
	    //$ch = curl_init("resultsservice.lottery.ie/ResultsService.asmx/GetProjectedJackpot");
//	    curl_setopt($ch, CURLOPT_PORT, $this->port);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	    curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		//curl_setopt($ch, CURLOPT_USERPWD, $this->admin_username . ':' . $this->admin_password);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	    $curl = curl_exec($ch);
	    curl_close($ch);
		
		try
		{		
			$xml = new SimpleXMLElement($curl);			
			return $xml;
		} catch(EXCEPTION $e)
		{
			exit($e);
			return Array();
		}	
	}	
}