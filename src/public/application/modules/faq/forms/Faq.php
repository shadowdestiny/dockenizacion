<?php

class Faq_Form_Faq extends Twitter_Form
{
	public function init()
	{
		$trans = Zend_Registry::Get('Zend_Translate');
		$arrLang = getAllLanguages("backend");

		$this->setMethod('post')
			->setAttrib('id', 'faq')
			->setName('translation')
			->setAttrib('enctype', 'multipart/form-data');

			$faq_id = new Zend_Form_Element_Hidden('faq_id');
			$faq_id->setValue(0);


			$name = new Zend_Form_Element_Text('name', array(
					'required'  => false,
					'label'	 => $trans->translate('name'),
					'filters'	=> array('StringTrim'),
					'size'		=> '20',
					'maxlength'	=> '65500',
					'minlength'	=> '2',
					'validators' => array(
						array('StringLength', false, array(2, 65500)),
					)
			));
			//$this->addElement($name);

			$active = new Zend_Form_Element_Select('active', array(
				'label'	 => $trans->translate('active'),
				'required'  => true,
				'multiOptions' => array('0' => 'no','1' => 'yes'),
			));
			//$this->addElement($active);

			$category = new Zend_Form_Element_Select('category', array(
				'label'	 => $trans->translate('category'),
				'required'  => true,
				'multiOptions' => array('basic' => 'basic'),
			));
			//$this->addElement($category);


			$this->addDisplayGroup(array(
					$faq_id,
					$name,
					$active,
					$category)
			, 'global',array('legend' => $trans->translate('general_information')));



			foreach($arrLang as $lang)
			{

				$question = new Zend_Form_Element_Text('question_'.$lang, array(
					'required'  => false,
					'label'	 => $trans->translate('question'),
					'filters'	=> array('StringTrim'),
					'size'		=> '20',
					'maxlength'	=> '65500',
					'minlength'	=> '2',
					'validators' => array(
						array('StringLength', false, array(2, 65500)),
					)
				));

				$answer = new Zend_Form_Element_Textarea('answer_'.$lang, array(
						'required'  => false,
						'label'	 => $trans->translate('answer'),
						'filters'	=> array('StringTrim'),
						'class'     => 'markitup',
						'size'		=> '20',
						'maxlength'	=> '65500',
						'minlength'	=> '2',
						'validators' => array(
							array('StringLength', false, array(2, 65500)),
						)
				));
				$req = '';
				if($lang == 'en'){
					$req = ' *' . $trans->translate('required');
				}
				//$legend = '<i class="flag flag-' . $lang .'" title="' . $trans->translate('language_'.$lang) . '" style="margin-left:2px;"/></i>' . $trans->translate('language_'.$lang);
				$this->addDisplayGroup(
					array($question,$answer),
					'cont_'.$lang,
					array(
						'legend' =>  $trans->translate('language_'.$lang) . $req
					)
				);

				$metaDisp = $this->getDisplayGroup('cont_'.$lang);
				$metaDisp->setDecorators(array(
					'FormElements',
					'Fieldset',
					array('HtmlTag',array('tag'=>'div','class'=>'languagebox closed lang_'.$lang)),
				));

			}


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
/**$globalDisp = $this->getDisplayGroup('global');

		$globalDisp->setDecorators(array(
			'FormElements',
			'Fieldset',
			array('HtmlTag',array('tag'=>'div','class'=>'col-md-12'))
        ));**/


        //$this->removeDecorator('HtmlTag');

		foreach($this->getDisplayGroups() as $dg)
		{
			$dg->setTranslator(null);
			$dg->setDisableTranslator(true);
		}

		foreach ($this->getElements() as $element) {
			$element->setTranslator(null);
			$element->setDisableTranslator(true);
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