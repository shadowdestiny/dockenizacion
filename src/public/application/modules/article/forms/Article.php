<?php

class Article_Form_Article extends Twitter_Form
{
	public function init()
	{

		//$this->setTranslator(null);
		//$this->setDefaultTranslator(null);
		//$this->setDisableTranslator(true);

		$trans = Zend_Registry::Get('Zend_Translate');

		$this->setMethod('post')
				->setAttrib('id', 'article')
				->setName('article')
				->setAttrib('enctype', 'multipart/form-data');



			$article_id = new Zend_Form_Element_Hidden('article_id');
			$article_id->setValue(0);

			$lang = new Zend_Form_Element_Hidden('lang');
			$lang->setValue("en");

			$published_on = new Zend_Form_Element_Text('published_on', array(
					'required'  => true,
					'label'	 => $trans->translate('published_on'),
					'filters'	=> array('StringTrim'),
					'class'     => 'datepicker',
					'size'		=> '20',
					'validators' => array(
						array('StringLength', false, array(3, 255)),
					)
			));


			$published = new Zend_Form_Element_Checkbox('published', array(
					'required'  => true,
					'label'	 => $trans->translate('Published'),
			));

			if(!hasAccess('acp_article_publish')){
				$published->setOptions(array('disabled' => 'disabled'));
			}
			
			$key = new Zend_Form_Element_Text('key', array(
					'required'  => true,
					'label'	 => 'Key:',
					'filters'	=> array('StringTrim'),
					'size'		=> '20',
					'maxlength'	=> '255',
					'minlength'	=> '3',
					'validators' => array(
						array('StringLength', false, array(3, 255)),
					)
			));
			$alias= new Zend_Form_Element_Text('alias', array(
					'required'  => true,
					'label'	 => 'URL Alias:',
					'filters'	=> array('StringTrim'),
					'size'		=> '20',
					'maxlength'	=> '255',
					'minlength'	=> '3',
					'validators' => array(
						array('StringLength', false, array(3, 255)),
					),
			));


			$title = new Zend_Form_Element_Text('title', array(
					'required'  => true,
					'label'	 => $trans->translate('title'),
					'filters'	=> array('StringTrim'),
					'size'		=> '20',
					'maxlength'	=> '255',
					'minlength'	=> '3',
					'validators' => array(
						array('StringLength', false, array(3, 255)),
					)
			));

			$image_url = new Zend_Form_Element_Text('image_url', array(
					'label'	 => $trans->translate('image')." URL",
					'filters'	=> array('StringTrim'),
					'size'		=> '20',
					'maxlength'	=> '255',
					'minlength'	=> '3',
					'validators' => array(
						array('StringLength', false, array(3, 255)),
					)
			));

			$image = new Zend_Form_Element_File('image', array(
					'label'	 => $trans->translate('image'),
					'class'     => 'fileupload',
			));

			$header = new Zend_Form_Element_Textarea('header', array(
					'label'	 => $trans->translate('header'),
					'filters'	=> array('StringTrim'),
					'size'		=> '20',
					'minlength'	=> '3',
					'maxlength'	=> '160',
					'class'     => 'markitup',
					'validators' => array(
						array('StringLength', false, array(3, 2048)),
					)
			));

			$content = new Zend_Form_Element_Textarea('content', array(
					'required'  => true,
					'label'	 =>  $trans->translate('content'),
					'filters'	=> array('StringTrim'),
					'size'		=> '20',
					'minlength'	=> '3',
					'class'     => 'markitup',
					'validators' => array(
						array('StringLength', false, array(3, 65554)),
					)
			));


			$page_title = new Zend_Form_Element_Text('page_title', array(
					'required'  => true,
					'label'	 => $trans->translate('page_title'),
					'filters'	=> array('StringTrim'),
					'size'		=> '20',
					'maxlength'	=> '255',
					'minlength'	=> '3',
					'class'     => 'markitup',
					'validators' => array(
						array('StringLength', false, array(3, 255)),
					)
			));


		$meta_description = new Zend_Form_Element_Textarea('meta_description', array(
					'required'  => true,
					'label'	 => 'Meta Description',
					'filters'	=> array('StringTrim'),
					'size'		=> '20',
					'minlength'	=> '3',
					'class'     => 'form-control',
					'validators' => array(
						array('StringLength', false, array(3, 2048)),
					)
			));

		$meta_keywords = new Zend_Form_Element_Text('meta_keywords', array(
					'required'  => true,
					'label'	 => 'Meta Keywords',
					'filters'	=> array('StringTrim'),
					'minlength'	=> '3',
					'validators' => array(
						array('StringLength', false, array(3, 2048)),
					)
			));

/**
		$acl = Zend_Registry::get("Zend_Acl");
		$roleList = $acl->getRoles();
		$roles=Array();
		foreach($roleList as $role)
		{
			if($acl->isAllowed($role,"article_admin_edit"))
			{
				$roles[]=$role;
			}
		}
**/

		$userList = Array();
		$obj_u = new User_Model_User();
		$select = $obj_u->getAdapter()->select();
		$select->from(Array("u"=>"users"),Array("user_id","role"));
		$select->join(Array("ud"=>"user_details"),"u.user_id=ud.user_id");
		//$select->where("u.role in (?)",$roles);
		$select->order("ud.first_name");
		$tmpList = $obj_u->getAdapter()->fetchAll($select);

		foreach($tmpList as $user)
		{
			$userList[$user['user_id']] = $user['first_name']." ".$user['last_name'];
		}

		$author = new Zend_Form_Element_Select('author', array(
					'required'  => true,
					'label'	 => $trans->translate('author'),
					//'multiOptions' => $userList,
			));
		$author->setTranslator(null);
		$author->setDisableTranslator(true);
		$author->setOptions(Array('multiOptions' => $userList));

		$submit = new Zend_Form_Element_Submit(
			'save',
			array(
				'label'	=> $trans->translate('submit'),
				'class'	=> 'pull-right',
				'required' => false,
				'ignore'   => true,
			)
		);

		$this->addDisplayGroup(array(
				$article_id,
				$lang,
				$key,
				$published_on,
				$published,
				$author)
		, 'global',array('legend' => $trans->translate('general_information')));

		$this->addDisplayGroup(array(
				$page_title,
				$alias,
				$meta_keywords,
				$meta_description)
		, 'meta',array('legend' =>  $trans->translate('meta_information')));

		$this->addDisplayGroup(array(
				$title,
				$image_url,
				$image,
				$header,
				$content

		), 'cont',array('legend' => $trans->translate('content_information')));

		$this->addElements(
		array(
			$submit
		));


		$globalDisp = $this->getDisplayGroup('global');
		$globalDisp->setDecorators(array(
			'FormElements',
			'Fieldset',
			array('HtmlTag',array('tag'=>'div','class'=>'col-md-6'))
        ));

		$metaDisp = $this->getDisplayGroup('meta');
		$metaDisp->setDecorators(array(
			'FormElements',
			'Fieldset',
			array('HtmlTag',array('tag'=>'div','class'=>'col-md-6'))
        ));

        //$this->removeDecorator('HtmlTag');

		foreach($this->getDisplayGroups() as $dg)
		{
			$dg->setTranslator(null);
			$dg->setDisableTranslator(true);
		}

		foreach ($this->getElements() as $element) {
			$element->setTranslator(null);
			$element->setDisableTranslator(true);
			//$element->removeDecorator('Label');
            //$element->removeDecorator('HtmlTag');
            //$element->removeDecorator('DtDdWrapper');
        }


	}
}


class Model_Validate_Db_NoRecordExists extends Zend_Validate_Db_Abstract {

    protected $_fields;

    function __construct($options) {

        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        }
        if (array_key_exists('fields',$options)) {
            $this->_fields =  $options['fields'];
            // to prevent parent from throwing missing option exception:
            $options['field'] = $this->_fields;
        }
        parent::__construct($options);
    }


    function isValid($value,$context=null) {


        $this->_setValue($value);
        /** FROM Zend_Validate_Db_Abstract: ///////////
         * Check for an adapter being defined. if not, fetch the default adapter.
         */

        if ($this->_adapter === null) {
            $this->_adapter = Zend_Db_Table_Abstract::getDefaultAdapter();
            if (null === $this->_adapter) {
                throw new Zend_Validate_Exception('No database adapter present');
            }
        }

        if (! $this->_fields) { // plural !
            $result = $this->_query($value);
            if ($result) {
                $this->_error(self::ERROR_RECORD_FOUND);
                return false;
            } else {
                return true;
            }
        }
        /**
         * Build select object
         */

        $select = new Zend_Db_Select($this->_adapter);


        $select->from($this->_table, $this->_fields, $this->_schema);
        // sole difference from parent implementation: add multiple WHERE clauses
        // note: we are assuming form field name == column name
        foreach ($this->_fields as $field) {
            $select->where("$field = ?",$context[$field]);
        }

        if ($this->_exclude !== null) {
            if (is_array($this->_exclude)) {
                $select->where($this->_adapter->quoteIdentifier($this->_exclude['field']).' != ?', $this->_exclude['value']);
            } else {
                $select->where($this->_exclude);
            }
        }
        $select->limit(1);

        $result = $this->_adapter->fetchRow($select, array(), Zend_Db::FETCH_ASSOC);
        if ($result) {
            $this->_error(self::ERROR_RECORD_FOUND);
               return false;
        } else {
            return true;
        }
    }
}