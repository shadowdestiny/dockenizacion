<?php
class User_Form_Oldusers extends Twitter_Form
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

        $this->addElement('password', 'password-repeat', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('StringLength', false, array(0, 50)),
            ),
            'required'   => true,
			'class'     => 'form-control',
            'label'      => $trans->translate('repeat_password') . ' *',
			'placeholder'      => $trans->translate('repeat_password') . ' *',
        ));

        $this->addElement('submit', 'submit', array(
            'required'	=> false,
            'ignore'	=> true,
			'class'		=> '',
            'label'		=> 'submit',
        ));
    }
}