<?

class Default_Model_Stats_Ticket_Lc extends Zend_Db_Table_Abstract
{
	protected $_name = 'stats_tickets_lc';
	protected $_primary=Array("draw_id","customer_id","ticket_type_id");


	public function getDraws($draw_id = NULL,$customer_id = 0,$type = 'all'){

		$obj_emd = new Lottery_Euromillions_Draw();

		$select = $this->select();

		if ($type == 'group'){
			$select->from('stats_tickets_lc',array(
				'draw_id',
				'product_id',
				new Zend_Db_Expr('SUM(add_to_cart) as add_to_cart'),
				new Zend_Db_Expr('SUM(remove_from_cart) as remove_from_cart'),
				new Zend_Db_Expr('SUM(validation_ok) as validation_ok'),
				new Zend_Db_Expr('SUM(validation_error) as validation_error'),
				new Zend_Db_Expr('SUM(bought) as bought'),
				new Zend_Db_Expr('SUM(bought_total_amount) as bought_total_amount'),
				new Zend_Db_Expr('SUM(won) as won'),
				new Zend_Db_Expr('SUM(won_total_amount) as won_total_amount')
			));
		}

		$select->order('draw_id desc');

		if ($draw_id){
			$select->where('draw_id=?' ,$draw_id);
		}
		if ($customer_id > 0){
			$select->where('customer_id=?' ,$customer_id);
		}


		$arrDraws = $this->fetchAll($select)->toArray();
		$drawdates = array_flip($obj_emd->calcDrawDates());

		foreach($arrDraws as $key => $draw){

			$date = $drawdates[$draw['draw_id']];
			$drawdate = new Zend_Date($date, false, 'de_DE');
			$drawdate = $drawdate->getTimestamp();
			$now = time();

			if ($drawdate < $now){
				$arrDraws[$key]['status'] = 'finished';
			} else {
				$arrDraws[$key]['status'] = 'open';
			}
			$arrDraws[$key]['date'] = $date;
			$arrDraws[$key]['profit_total_amount'] = '0';
		}

		return $arrDraws;
	}


}