<?

class Lottery_Model_Euromillions_Draws extends Zend_Db_Table_Abstract
{
	protected $_name = 'lottery_draws';
	protected $_primary="draw_id";

	public function getNextJackpot()
	{
		$select = $this->select();
		$select->where("published=?",0);
		$select->order("draw_id desc");
		$select->limit(1);
		$data = $this->fetchRow($select);
		
		if($data)
		{
			return $data->jackpot;
		}
		else
		{
			return "";
		}
	}
}