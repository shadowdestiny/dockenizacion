<?php
class User_Form_Myaccount_Changepassword extends Twitter_Form
{
    public function init()
    {
		$layout = Zend_Layout::getMvcInstance();
		$is_popup_id = ($this->_isPopup) ? 'changepassword_popup' : 'changepassword';
		
		 $view = $layout->getView();
			   $trans = Zend_Registry::Get('Zend_Translate');
			   $this->setMethod('post')
				   ->setAttrib('id', $is_popup_id)
				   ->setName('changepassword')
				   ->setAttrib('enctype', 'multipart/form-data');

        $this->setMethod('post');

		$this->addElement('hidden', 'act', array(
			'value'=>"newpassword"
		));
		
		$is_popup = ($this->_isPopup) ? '1' : '0';

		$this->addElement('hidden', 'formpost', array(
			'value' => '1'
		));

		$this->addElement('hidden', 'popup', array(
			'value' => $is_popup
		));		
		
        $this->addElement('password', 'tmppassword', array(
            'filters'    => array('StringTrim'),
            'validators' => array
			(
                array('StringLength', false, array(0, 50))
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