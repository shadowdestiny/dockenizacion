<?php

class Faq_IndexController extends Zend_Controller_Action
{

    public function init()
    {
		if(!hasAccess("faq"))
		{
			$this->redirect($this->view->Url(Array(),"NoAccess",true));
		}
    }

    public function indexAction()
    {
		// Stats
		$obj_s = new Default_Model_Stats();
		$obj_s->set("show_faq");

		$arrList = Array();

		$request = $this->getRequest();

        $obj_f = new Faq_Model_Faq();
		$obj_fd = new Faq_Model_Faq_Data();

		$select = $obj_f->select();
		$select->where("active=?",1);
		$select->order("pos");
		$faqList = $obj_f->fetchAll($select);

		if($faqList->count()>0)
		{
			foreach($faqList as $item)
			{

				$select = $obj_fd->select();
				$select->where("faq_id=?",$item->faq_id);
				$select->where("lang=?",$request->getParam("lang","en"));
				$faqData = $obj_fd->fetchRow($select);

				if($faqData)
				{
					$arrList[]=Array(
					"question"=>$faqData->question,
					"answer"=>$faqData->answer
					);
				}
			}
			$this->view->arrList = $arrList;
		} else {
			$this->view->arrList = $arrList;
		}
    }
}