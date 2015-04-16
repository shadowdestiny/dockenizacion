<?

class Zend_View_Helper_Teaser
{
	public $view;

	public function Teaser()
	{
		return $this;
	}

	public function News($position = 'content', $limit = 3)
	{

		$lang = Zend_Registry::get('lang');

		$obj_n = new News_Model_News();
		$obj_nd = new News_Model_NewsDetails();

		$select =$obj_n->getAdapter()->select();
		$select->from( Array("n" => "news"), Array("news_id"));
		$select->join(Array("nd"=>"news_details"),"nd.news_id=n.news_id",Array());

		$select->where("n.published=1");
		$select->where("nd.lang=?",$lang);
		$select->order("nd.published_on desc");
		$select->limit($limit);

		$data = $obj_n->getAdapter()->fetchAll($select);


		foreach($data as $item)
		{

			$select = $obj_nd->select();
			$select->where("news_id=?",$item['news_id']);
			$select->where("lang=?",$lang);
			$news = $obj_n->fetchRow($select);

			$tmp = $news->toArray();
			$tmp['detail_link'] = $this->view->url(Array("alias"=>$news['alias']),"News_Show");

			$arrList[]=$tmp;
		}

		$this->view->list = $arrList;
		return $this->view->render('news/' . $position . 'teaser.phtml');

	}

	public function setView(Zend_View_Interface $view)
	{
		$this->view = $view;
	}
}
