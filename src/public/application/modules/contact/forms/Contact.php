<?php


class Contact_Form_Contact extends Twitter_Form
{
	/**
	 *
	 * @var integer customer_id
	 */
	private $_isPopup = false;

	public function __construct(array $params = array(), $options=null)
	{
		$this->_isPopup = $params['isPopup'];
		parent::__construct($options);
	}

	public function init()
	{
		$trans = Zend_Registry::Get('Zend_Translate');
		$is_popup_id = ($this->_isPopup) ? 'contact_popup' : 'contact';

		$this->setMethod('post')
			->setAttrib('id', $is_popup_id)
			->setName('contact')
			->setAttrib('enctype', 'multipart/form-data');

			$is_popup = ($this->_isPopup) ? '1' : '0';

			$formpost = new Zend_Form_Element_Hidden('formpost', array(
					'value' => '1'
			));

			$popup = new Zend_Form_Element_Hidden('popup', array(
					'value' => $is_popup
			));


			//$checkEmail = new Zend_Validate_Db_NoRecordExists(array('table' => "users",'field' => 'username'));
			//$checkEmail->setMessages(array("recordFound" => $trans->translate('email_exist').' '.$trans->translate('acp_change_email_username')));

			$email = new Zend_Form_Element_Text('email', array(
					'required'  => true,
					'placeholder'	 => $trans->translate('email') . ' *',
					'filters'	=> array('StringTrim'),
					'size'		=> '20',
					'maxlength'	=> '255',
					'validators' => array(
						array('StringLength', false, array(2, 255)),
						array('EmailAddress'),
						//array($checkEmail, false),
					)
			));

			$message = new Zend_Form_Element_Textarea('message', array(
					'required'  => true,
					'class'		=> 'form-control',
					'placeholder'	 => $trans->translate('message') . ' *',
					'filters'	=> array('StringTrim'),
					'maxlength'	=> '1000',
					'validators' => array(
						array('StringLength', false, array(2, 1000))
					)
			));

			$name = new Zend_Form_Element_Text('name', array(
					'required'  => true,
					'placeholder'	 => $trans->translate('name') . ' *',
					'filters'	=> array('StringTrim'),
					'size'		=> '20',
					'maxlength'	=> '20',
					'validators' => array(
						array('StringLength', false, array(2, 20)),
					)
			));

			$subject = new Zend_Form_Element_Text('subject', array(
					'required'  => true,
					'placeholder'	 => $trans->translate('subject') . ' *',
					'filters'	=> array('StringTrim'),
					'size'		=> '20',
					'maxlength'	=> '50',
					'validators' => array(
						array('StringLength', false, array(2, 50)),
					)
			));



			$submit = new Zend_Form_Element_Submit(
				'submit',
				array(
					'label'	=> $trans->translate('submit'),
					'required' => false,
					'ignore'   => true,
				)
			);

			$this->addElements(
				array(
					$formpost,
					$popup,
					$name,
					$email,
					$subject,
					$message,
					$submit
				));

	}

}