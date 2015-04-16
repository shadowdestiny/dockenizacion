<?php


class User_Form_Registration extends Twitter_Form
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
		$is_popup_id = ($this->_isPopup) ? 'registration_popup' : 'registration';

		$this->setDecorators(array(array('viewScript', array('viewScript' => '/user/auth/registration_form.phtml', array('formId' => $is_popup_id)))));

		/* $this->setDecorators(array(array(
                'viewScript',
                array(
                    'viewScript' => '/user/auth/registration_form.phtml',
                    array('formId' => 'CustomForm', 'formClass' => 'customForm')
            ))));
		*/
		$this->setMethod('post')
			->setAttrib('id', $is_popup_id)
			->setName('registration')
			->setAttrib('enctype', 'multipart/form-data');

			$is_popup = ($this->_isPopup) ? '1' : '0';

			$formpost = new Zend_Form_Element_Hidden('formpost', array(
					'value' => '1'
			));

			$popup = new Zend_Form_Element_Hidden('popup', array(
					'value' => $is_popup
			));


			$checkEmail = new Zend_Validate_Db_NoRecordExists(array('table' => "users",'field' => 'username'));
			$checkEmail->setMessages(array("recordFound" => $trans->translate('email_exist').' '.$trans->translate('acp_change_email_username')));

			$country= new Zend_Form_Element_Select('country', array(
					'required'  => true,
					'placeholder'	 => $trans->translate('country') . ' *',
					'validators' => array(
						array('StringLength', false, array(2, 255)),
					)
			));



			$email = new Zend_Form_Element_Text('email', array(
					'required'  => true,
					'placeholder'	 => $trans->translate('email') . ' *',
					'filters'	=> array('StringTrim'),
					'class'		=> 'equal',
					'maxlength'	=> '255',
					'validators' => array(
						array('StringLength', false, array(2, 255)),
						array('EmailAddress'),
						array($checkEmail, false),
					)
			));

			$email_equal = new Zend_Form_Element_Text('email_equal', array(
					'required'  => true,
					'placeholder'	 => $trans->translate('repeat_email') . ' *',
					'filters'	=> array('StringTrim'),
					'maxlength'	=> '255',
					'validators' => array(
						array('StringLength', false, array(2, 255)),
						array('EmailAddress'),
						array('Identical', false, array('token' => 'email')),
					)
			));

			$password = new Zend_Form_Element_Password('password', array(
					'required'  => true,
					'placeholder'	 => $trans->translate('password') . ' *',
					'filters'	=> array('StringTrim'),
					'class'	=> 'form-control',
					'maxlength'	=> '20',
					'validators' => array(
						array('StringLength', false, array(2, 20)),
						array('Regex', false, array('/^[a-zA-Z0-9\-_ !\"§$%&()=#+*.,:;@€\/]*$/'))
					)
			));

			$firstname = new Zend_Form_Element_Text('firstname', array(
					'required'  => true,
					'placeholder'	 => $trans->translate('firstname') . ' *',
					'filters'	=> array('StringTrim'),
					'maxlength'	=> '30',
					'validators' => array(
						array('StringLength', false, array(2, 20)),
					)
			));


			for($i=1;$i<=31;$i++)
			{
				$days[$i]=$i;
			}

			$birth_day = new Zend_Form_Element_Select('birth_day', array(
				'required'  => true,
				'multiOptions' => $days,
			));


			for($i=1;$i<=12;$i++)
			{
				$months[$i]=$trans->translate("month_".$i);;
			}

			$birth_month = new Zend_Form_Element_Select('birth_month', array(
				'required'  => true,
				'multiOptions' => $months,
			));

			for($i=date("Y")-17;$i>=(date("Y")-100);$i--)
			{
				$years[$i]=$i;
			}

			$birth_year = new Zend_Form_Element_Select('birth_year', array(
				'required'  => true,
				'multiOptions' => $years,
				));

			$gender = new Zend_Form_Element_Radio('gender', array(
					'required'  => true,
					'label'	 => $trans->translate('gender'),
					'multiOptions' => Array("m"=>$trans->translate('men'),"f"=>$trans->translate('women')),
					//'validators' => array(
					//	array('StringLength', false, array(2, 20)),
					//)
				));


			$newsletter = new Zend_Form_Element_Checkbox('newsletter', array(
					'required'  => true,
					'label'	 => $trans->translate('registration_newsletter_checkbox'),
					'value' => 0,
				));



			$submit = new Zend_Form_Element_Submit(
				'save',
				array(
					'label'	=> $trans->translate('register_button'),
					'required' => false,
					'ignore'   => true,
				)
			);

			$this->addElements(
				array(
					$formpost,
					$popup,
					$gender,
					$country,
					$firstname,
					$birth_day,
					$birth_month,
					$birth_year,
					$email,
					$email_equal,
					$password,
					$newsletter,
					$submit
				));


			//foreach ($this->getElements() as $element) {
	//			$element->removeDecorator('Label');
			   //$/element->removeDecorator('HtmlTag');
				//$element->removeDecorator('DtDdWrapper');
			//}


	}


}