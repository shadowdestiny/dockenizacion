<?php
class User_Form_Login extends Twitter_Form
{
    public function init()
    {
		$layout = Zend_Layout::getMvcInstance();
		$view = $layout->getView();
		$trans = Zend_Registry::Get('Zend_Translate');
		$this->setMethod('post')
			->setAttrib('id', 'login')
			->setName('login')
			->setAttrib('enctype', 'multipart/form-data');


        $this->setName("login");
        $this->setMethod('post');

		$this->addElement('hidden', 'act', array(
			'value'=>"login"
		));
		$this->addElement('hidden', 'loginact', array(
			'value'=>"myaccount"
		));

        $this->addElement('text', 'username', array(
            'filters'    => array('StringTrim', 'StringToLower'),
            'validators' => array(
                array('StringLength', false, array(0, 50)),
				array('EmailAddress'),
            ),
            'required'   => true,
            'label'      => 'username',
			'placeholder'      => 'username',
        ));

        $this->addElement('password', 'password', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('StringLength', false, array(0, 50)),
            ),
            'required'   => true,
			'class'     => 'form-control',
            'label'      => 'password',
			'placeholder'      => 'password',
        ));

        $this->addElement('submit', 'submit', array(
            'required'	=> false,
            'ignore'	=> true,
			'class'		=> '',
            'label'		=> 'login',
        ));
    }
}