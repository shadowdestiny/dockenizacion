<?

class Billing_Model_Transactions extends Zend_Db_Table_Abstract
{
	protected $_name = "transactions";
	protected $_primary="transaction_id";
	
	public function addTransaction($transData)
	{
	//$transaction_type="",$user_id=0,$amount=0,$biller="",$biller_transaction_id=0,$order_id=0
		
		if(is_array($transData) && $transData['transaction_type']<>"")
		{
		
			if($transData['transaction_date'])
			{			
				$date = new Zend_Date($transData['transaction_date']);
				$trans_date = $date->toString('yyyyMMddHHmmss');
			} else {
				$trans_date = Zend_Date::now()->toString('yyyyMMddHHmmss',"DE_de");
			}
			
			$arrData = $transData;
			$arrData["transaction_date"]=$trans_date;
						
			try
			{
				$transaction_id = $this->insert($arrData);
				
				// mfg  log in file here
				return $transaction_id;
			} catch(EXCEPTION $e)
			{
				echo $e;
				return false;
			}
		}
	}
	
	public function setOrderIdToTransactionId($transaction_id=0,$order_id=0)
	{
		if($transaction_id>0 && $order_id>0)
		{
			$data = $this->fetchRow("transaction_id=".$transaction_id);
			if($data)
			{
				$this->update(Array("order_id"=>$order_id),"transaction_id=".$transaction_id);
				return true;
				
			} else {
				// mfg log
			}
		} else {
			// mfg log
		}
	}
}