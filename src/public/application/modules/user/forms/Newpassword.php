<?php
class User_Form_Newpassword extends Twitter_Form
{
    public function init()
    {
		$layout = Zend_Layout::getMvcInstance();
		
		 $view = $layout->getView();
			   $trans = Zend_Registry::Get('Zend_Translate');
			   $this->setMethod('post')
				   ->setAttrib('id', 'newpassword')
				   ->setName('newpassword')
				   ->setAttrib('enctype', 'multipart/form-data');


        $this->setMethod('post');

		$this->addElement('hidden', 'act', array(
			'value'=>"newpassword"
		));
	

		$this->addElement('hidden', 'formpost', array(
			'value' => '1'
		));

		$checkPw = new Zend_Validate_Db_RecordExists(array('table' => "users",'field' => 'tmp_password'));
		$checkPw->setMessages(array("recordFound" => $trans->translate('username_exist').' '.$trans->translate('wrong_password')));
		
        $this->addElement('password', 'tmppassword', array(
            'filters'    => array('StringTrim'),
            'validators' => array
			(
                array('StringLength', false, array(0, 50)),
				array($checkPw, false),
            ),
            'required'   => true,
			'class'     => 'form-control',
            'label'      => $trans->translate('password') . ' *',
			'placeholder'      => $trans->translate('password') . ' *',
        ));

		
		
        $this->addElement('password', 'password', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('StringLength', false, array(0, 50)),
            ),
            'required'   => true,
			'class'     => 'form-control',
            'label'      => $trans->translate('new_password') . ' *',
			'placeholder'      => $trans->translate('new_password') . ' *',
        ));

		
		
        $this->addElement('password', 'passwordrepeat', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('StringLength', false, array(0, 50)),
				array('Identical', false, array('token' => 'password')),
            ),
            'required'   => true,
			'class'     => 'form-control',
            'label'      => $trans->translate('repeat_password') . ' *',
			//'placeholder'      => $trans->translate('repeat_password') . ' *',
        ));

        $this->addElement('submit', 'submit', array(
            'required'	=> false,
            'ignore'	=> true,
			'class'		=> '',
            'label'		=> 'submit',
        ));
    }
}