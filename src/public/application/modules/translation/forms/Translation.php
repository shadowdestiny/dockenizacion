<?php

class Translation_Form_Translation extends Twitter_Form
{
	public function init()
	{
		$trans = Zend_Registry::Get('Zend_Translate');

		$this->setMethod('post')
			->setAttrib('id', 'translation')
			->setName('translation')
			->setAttrib('enctype', 'multipart/form-data');



			$translation_id = new Zend_Form_Element_Hidden('translation_id');
			$translation_id->setValue(0);

			$arrLang = getAllLanguages("backend");

			$key = new Zend_Form_Element_Text("key", array(
					'required'  => false,
					'label'	 => "Key:",
					'filters'	=> array('StringTrim'),
					'size'		=> '20',
					'maxlength'	=> '65500',
					'minlength'	=> '2',
					'validators' => array(
						array('StringLength', false, array(2, 65500)),
					)
			));
			$this->addElement($key);

			foreach($arrLang as $lang)
			{

				$title = new Zend_Form_Element_Text('value_'.$lang, array(
						'required'  => false,
						'label'	 => $trans->translate('language_'.$lang),
						'filters'	=> array('StringTrim'),
						'size'		=> '20',
						'maxlength'	=> '65500',
						'minlength'	=> '2',
						'validators' => array(
							array('StringLength', false, array(2, 65500)),
						)
				));
				$this->addElement($title);
		}

		$this->addElement($translation_id);



		$submit = new Zend_Form_Element_Submit(
			'save',
			array(
				'label'	=> $trans->translate('submit'),
				'class'	=> 'pull-right',
				'required' => false,
				'ignore'   => true,
			)
		);

		$this->addElement($submit);
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