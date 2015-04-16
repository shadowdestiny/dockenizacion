<?

class Default_Model_Stats_Data extends Zend_Db_Table_Abstract
{
	protected $_name = 'stats_data';
	protected $_primary=Array("year","month","day","hour","stats_type_id","customer_id");

}