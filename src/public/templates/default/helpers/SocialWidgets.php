<?

class Zend_View_Helper_SocialWidgets
{
	public $view;

	public function SocialWidgets()
	{

		return $this;
	}

	public function addThis()
	{

		$c = '<div class="socialbar">
				<!-- AddThis Button BEGIN -->
				<div class="addthis_toolbox addthis_default_style ">
				<a class="addthis_button_preferred_1"></a>
				<a class="addthis_button_preferred_2"></a>
				<a class="addthis_button_preferred_3"></a>
				<a class="addthis_button_preferred_4"></a>
				<a class="addthis_button_compact"></a>
				<a class="addthis_counter addthis_bubble_style"></a>
				</div>
				<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4f07108463898caa"></script>
				<!-- AddThis Button END -->
			</div>';
		return $c;
	}

	public function setView(Zend_View_Interface $view)
	{
		$this->view = $view;
	}
}