<?

class Zend_View_Helper_Selectmenu
{
	public $view;

	/**
	 *
	 * @param unknown_type $name
	 * @param unknown_type $arrData
	 * @param unknown_type $defaultValue
	 * @param unknown_type $class
	 */
	public function Selectmenu($name = NULL, $arrData = array(), $defaultValue="", $class="",$placeholder="")
	{
		if($name === NULL) {
			return $this;
		}

		if ($class == ''){$class='select';}
		elseif ($class == 'error'){$class='error select';}

		$c='<select id="'.$name.'" name="'.$name.'" class="'.$class.'">';

		if ($placeholder != ''){
			$c.='<option value=""> - '.$placeholder.' - </option>';
		}
		foreach($arrData as $id => $value)
		{
			$classt = "";
			$selected="";
  			if($id == $defaultValue){ $selected=' selected="selected"';}
			if($class == 'lang' || $class == 'lang overlay' ) { $classt=' class="'.$id.'"'; }

			$c.='<option value="'.$id.'"'.$selected.$classt.'>'.$value.'</option>';
		}
		$c.='</select>';
		return $c;
	}

	public function getMulti($name, array $arrData, $defaultValue="", $class="") {
		if ($class == ''){$class='select';}
		elseif ($class == 'error'){$class='error select';}

		$c='<select id="' . $name .'" name="'.$name.'[]" class="'.$class.'" size="'.count($arrData).'" multiple="multiple">';
		foreach($arrData as $id => $value)
		{
			$classt = "";
			$selected="";
  			if(in_array($id, $defaultValue)){ $selected=' selected="selected"';}
			if($class == 'lang' || $class == 'lang overlay' ) { $classt=' class="'.$id.'"'; }

			$c.='<option value="'.$id.'"'.$selected.$classt.'>'.$value.'</option>';
		}
		$c.='</select>';
		return $c;
	}

	public function setView(Zend_View_Interface $view)
	{

		$this->view = $view;
	}
}
