<?php
class User_Form_Changemail extends Twitter_Form
{
    public function init()
    {
		$layout = Zend_Layout::getMvcInstance();
		$view = $layout->getView();
		$trans = Zend_Registry::Get('Zend_Translate');
		$this->setMethod('post')
			->setAttrib('id', 'changemail')
			->setName('changemail')
			->setAttrib('enctype', 'multipart/form-data');


        $this->setName("changemail");
        $this->setMethod('post');

		$checkEmail = new Zend_Validate_Db_NoRecordExists(array('table' => "users",'field' => 'username'));
		$checkEmail->setMessages(array("recordFound" => $trans->translate('email_exist').' '.$trans->translate('acp_change_email_username')));

		$this->addElement('hidden', 'act', array(
			'value'=>"changemail"
		));

		$this->addElement('hidden', 'formpost', array(
			'value'=>"1"
		));

		$this->addElement('text','email', array(
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

		$this->addElement('text','email_equal', array(
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

        $this->addElement('submit', 'submit', array(
            'required'	=> false,
            'ignore'	=> true,
			'class'		=> '',
            'label'		=> 'submit',
        ));
    }
}