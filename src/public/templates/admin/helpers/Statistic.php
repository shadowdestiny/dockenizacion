<?

class Zend_View_Helper_Statistic
{
	public $view;
	private $statsData = Array();

	public function Statistic()
	{
		return $this;
	}

	public function setData($data){
		$this->statsData = $data;
	}

	public function resetData($data){
		$this->statsData = Array();
	}

	public function getTable($display, $keys = NULL)
	{
		if($keys == NULL){
			$keys = reset($this->statsData['data']);
			$keys = array_keys($keys);
		}

		return $this->view->partial('/default/adminstats/table.phtml', array('data' => $this->statsData, 'keys' => $keys, 'datetype' => $display));
	}

	public function getTotal($keys = NULL){
		if($keys != NULL){
			$count = 0;
			foreach($keys as $key){
				$count = $this->statsData['total'][$key] + $count;
			}
			return $count;
		}
	}


	public function setView(Zend_View_Interface $view)
	{
		$this->view = $view;
	}
}