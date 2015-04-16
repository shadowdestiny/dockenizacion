<?php


class User_Form_Myaccount_Personal extends Twitter_Form
{
	/**
	 *
	 * @var integer customer_id
	 */
	private $_isPopup = false;

	public function __construct(array $params = array(), $options=null)
	{
		$this->_isPopup = isset($params['isPopup']) ? $params['isPopup'] : false;
		parent::__construct($options);
	}

	public function init()
	{
		$trans = Zend_Registry::Get('Zend_Translate');
		$is_popup_id = ($this->_isPopup) ? 'completeprofile_popup' : 'completeprofile';

//		$this->setDecorators(array(array('viewScript', array('viewScript' => '/user/auth/registration_form.phtml', array('formId' => $is_popup_id)))));

		/* $this->setDecorators(array(array(
                'viewScript',
                array(
                    'viewScript' => '/user/auth/registration_form.phtml',
                    array('formId' => 'CustomForm', 'formClass' => 'customForm')
            ))));
		*/
		$this->setMethod('post')
			->setAttrib('id', $is_popup_id)
			->setName('personaldata')
			->setAttrib('enctype', 'multipart/form-data');

			$is_popup = ($this->_isPopup) ? '1' : '0';

			$formpost = new Zend_Form_Element_Hidden('formpost', array(
					'value' => '1'
			));

			$popup = new Zend_Form_Element_Hidden('popup', array(
					'value' => $is_popup
			));


			$country= new Zend_Form_Element_Select('country', array(
					'required'  => true,
					'placeholder'	 => $trans->translate('country') . ' *',
					'validators' => array(
						array('StringLength', false, array(2, 255)),
					)
			));



			$lastname = new Zend_Form_Element_Text('last_name', array(
					'required'  => true,
					'placeholder'	 => $trans->translate('lastname') . ' *',
					'filters'	=> array('StringTrim'),
					'class'		=> 'equal',
					'maxlength'	=> '255',
					'validators' => array(
						array('StringLength', false, array(2, 255)),
					)
			));

			$firstname = new Zend_Form_Element_Text('first_name', array(
					'required'  => true,
					'placeholder'	 => $trans->translate('firstname') . ' *',
					'filters'	=> array('StringTrim'),
					'maxlength'	=> '30',
					'validators' => array(
						array('StringLength', false, array(2, 20)),
					)
			));


			$street = new Zend_Form_Element_Text('street', array(
					'required'  => true,
					'placeholder'	 => $trans->translate('street_number') . ' *',
					'filters'	=> array('StringTrim'),
					'maxlength'	=> '30',
					'validators' => array(
						array('StringLength', false, array(2, 20)),
					)
			));

			$zip = new Zend_Form_Element_Text('zip', array(
					'required'  => true,
					'placeholder'	 => $trans->translate('zip') . ' *',
					'filters'	=> array('StringTrim'),
					'maxlength'	=> '30',
					'validators' => array(
						array('StringLength', false, array(2, 20)),
					)
			));

			$city = new Zend_Form_Element_Text('city', array(
					'required'  => true,
					'placeholder'	 => $trans->translate('city') . ' *',
					'filters'	=> array('StringTrim'),
					'maxlength'	=> '30',
					'validators' => array(
						array('StringLength', false, array(2, 20)),
					)
			));



			$submit = new Zend_Form_Element_Submit(
				'save',
				array(
					'label'	=> $trans->translate('save'),
					'class' => 'submit',
					'required' => false,
					'ignore'   => true,
				)
			);
			$close = new Zend_Form_Element_Button(
				'close',
				array(
					'label'	=> $trans->translate('close'),
					'class' => 'toggle btn-default',
					'data-target' => 'profile',
					'required' => false,
					'ignore'   => true,
				)
			);

			$this->addElements(
				array(
					$formpost,
					$popup,
					$firstname,
					$lastname,
					$street,
					$zip,
					$city,
					$country,
					$submit,
					$close
				));


			foreach ($this->getElements() as $element) {
	//			$element->removeDecorator('Label');
			   //$/element->removeDecorator('HtmlTag');
				//$element->removeDecorator('DtDdWrapper');
			}


	}


}