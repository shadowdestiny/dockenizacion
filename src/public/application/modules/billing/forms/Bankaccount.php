<?php

class Billing_Form_Bankaccount extends Twitter_Form
{
	private $_isPopup = false;

	public function __construct(array $params = array(), $options=null)
	{
		$this->_isPopup = $params['isPopup'];
		parent::__construct($options);
	}

	public function init()
	{
		$layout = Zend_Layout::getMvcInstance();
		$view = $layout->getView();

		$is_popup_id = ($this->_isPopup) ? 'accountform_popup' : 'accountform';
		$trans = Zend_Registry::Get('Zend_Translate');
		$this->setMethod('post')
			->setAttrib('id', $is_popup_id)
			->setName('accountform')
			->setAttrib('enctype', 'multipart/form-data');

			$formpost = new Zend_Form_Element_Hidden('formpost', array(
					'value' => '1'
			));

			$popup = new Zend_Form_Element_Hidden('popup', array(
					'value' => $this->_isPopup
			));

			$step = new Zend_Form_Element_Hidden('step', array(
					'value' => '2'
			));

			$country = new Zend_Form_Element_Select('country', array(
				'required'		=> true,
				'label'			=> $trans->translate('bank_country') . ' *',
				'validators'	=> array(
					array('StringLength', false, array(2, 255)),
				)
			));

			$bankname = new Zend_Form_Element_Text('bankname', array(
				'required'		=> true,
				'label'			=> $trans->translate('bank_bankname') . ' *',
				'validators'	=> array(
					array('StringLength', false, array(2, 255)),
				),
				'filters' => Array(
					"StripNewlines",
					"StripTags",
					"StringTrim"
				)
			));

			$accountholder = new Zend_Form_Element_Text('accountholder', array(
				'disabled'		=> true,
				'label'			=> $trans->translate('account_holder') . ' *',
				'validators'	=> array(
					array('StringLength', false, array(2, 255)),
				)
			));

			$accountnumber = new Zend_Form_Element_Text('accountnumber', array(
				'required'		=> false,
				'label'			=> $trans->translate('account_number'),
				'validators'	=> array(
					array('StringLength', false, array(2, 255)),
				),
				'filters' => Array(
					"StripNewlines",
					"StripTags",
					"StringTrim"
				)
			));

			$bic = new Zend_Form_Element_Text('bic', array(
				'required'		=> true,
				'label'			=> $trans->translate('swift_bic'),
				'validators'	=> array(
					array('StringLength', false, array(2, 255)),
				),
				'filters' => Array(
					"StripNewlines",
					"StripTags",
					"StringTrim"
				)
			));

			$iban = new Zend_Form_Element_Text('iban', array(
				'required'		=> false,
				'label'			=> $trans->translate('iban'),
				'validators'	=> array(
					array('StringLength', false, array(2, 255)),
				),
				'filters' => Array(
					"StripNewlines",
					"StripTags",
					"StringTrim"
				)
			));

			$iban_label = new Zend_Form_Element_Note('iban_label', array(
				'value' => '<ul class="info"><li>' . $trans->translate('billing_account_iban_countries') . ' <br /><small><a href="#Modal" class="toggleAccount">' . $trans->translate('billing_account_iban_countries_link') . '</a></small></li></ul>'
			));


			$account_label = new Zend_Form_Element_Note('account_label', array(
				'value' => '<ul class="info"><li>' . $trans->translate('billing_account_accountnumber_label'). '</li></ul>'
			));
			$submit = new Zend_Form_Element_Submit(
				'save',
				array(
					'label'	=> $trans->translate('add'),
					'class'	   => 'save',
					'required' => false,
					'ignore'   => true,
				)
			);
			$back = new Zend_Form_Element_Submit(
				'back',
				array(
					'label'	=> $trans->translate('change'),
					'class'	   => 'save pull-left',
					'required' => false,
					'ignore'   => true,
				)
			);
		//	$this->addDisplayGroup(array($accountholder,$iban,$or,$accountnumber), 'pcinfo', array('disableLoadDefaultDecorators' => true,'legend' => 'Other Block'));

			$this->addElements(
				array(
					$formpost,
					$popup,
					$step,
					$accountholder,
					$country,
					$bankname,
					$bic,
					$iban_label,
					$iban,
					$account_label,
					$accountnumber,
					$back,
					$submit
			));
	}

}